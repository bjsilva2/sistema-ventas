<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\MovimientoCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    public function index()
    {
        $cajaAbierta = Caja::where('usuario_id', auth()->id())
            ->where('estado', 'ABIERTA')
            ->with('movimientos')
            ->latest('id')
            ->first();

        $cajas = Caja::with('usuario')
            ->orderBy('id', 'desc')
            ->get();

        $ingresos = 0;
        $egresos = 0;
        $saldoActual = 0;

        if ($cajaAbierta) {

            $ingresos = $cajaAbierta->movimientos()
                ->where('tipo', 'INGRESO')
                ->sum('monto');

            $egresos = $cajaAbierta->movimientos()
                ->where('tipo', 'EGRESO')
                ->sum('monto');

            $saldoActual =
                $cajaAbierta->monto_inicial
                + $ingresos
                - $egresos;
        }

        return view('caja.index', compact(
            'cajaAbierta',
            'cajas',
            'ingresos',
            'egresos',
            'saldoActual'
        ));
    }

    public function abrir(Request $request)
    {
        $request->validate([
            'monto_inicial' => 'required|numeric|min:0',
        ]);

        $existeCaja = Caja::where('usuario_id', auth()->id())
            ->where('estado', 'ABIERTA')
            ->exists();

        if ($existeCaja) {
            return back()->withErrors([
                'error' => 'Ya tienes una caja abierta.'
            ]);
        }

        Caja::create([
            'usuario_id' => auth()->id(),
            'fecha_apertura' => now(),
            'monto_inicial' => $request->monto_inicial,
            'estado' => 'ABIERTA',
        ]);

        return redirect()
            ->route('caja.index')
            ->with('success', 'Caja abierta correctamente.');
    }

    public function movimiento(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:INGRESO,EGRESO',
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0.01',
        ]);

        $caja = Caja::where('usuario_id', auth()->id())
            ->where('estado', 'ABIERTA')
            ->first();

        if (!$caja) {
            return back()->withErrors([
                'error' => 'No tienes una caja abierta.'
            ]);
        }

        MovimientoCaja::create([
            'caja_id' => $caja->id,
            'tipo' => $request->tipo,
            'concepto' => $request->concepto,
            'monto' => $request->monto,
            'fecha' => now(),
        ]);

        return redirect()
            ->route('caja.index')
            ->with('success', 'Movimiento registrado correctamente.');
    }

    public function cerrar()
    {
        $caja = Caja::where('usuario_id', auth()->id())
            ->where('estado', 'ABIERTA')
            ->first();

        if (!$caja) {
            return back()->withErrors([
                'error' => 'No tienes una caja abierta.'
            ]);
        }

        DB::transaction(function () use ($caja) {

            $ingresos = $caja->movimientos()
                ->where('tipo', 'INGRESO')
                ->sum('monto');

            $egresos = $caja->movimientos()
                ->where('tipo', 'EGRESO')
                ->sum('monto');

            $montoFinal =
                $caja->monto_inicial
                + $ingresos
                - $egresos;

            $caja->update([
                'fecha_cierre' => now(),
                'monto_final' => $montoFinal,
                'estado' => 'CERRADA',
            ]);
        });

        return redirect()
            ->route('caja.index')
            ->with('success', 'Caja cerrada correctamente.');
    }
}