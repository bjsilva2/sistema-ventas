<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\MovimientoCaja;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    public function index()
    {
        $cajaAbierta = Caja::where('usuario_id', auth()->id())
            ->where('estado', 'ABIERTA')
            ->with(['movimientos', 'usuario'])
            ->latest('id')
            ->first();

        $cajas = Caja::with(['usuario', 'movimientos'])
            ->orderBy('id', 'desc')
            ->get();

        $ventasEfectivo = 0;
        $ventasQr = 0;
        $egresos = 0;
        $totalVendidoCobrado = 0;
        $efectivoEnCaja = 0;
        $saldoQr = 0;

        if ($cajaAbierta) {
            $fechaInicio = $cajaAbierta->fecha_apertura;
            $fechaFin = now();

            $ventasEfectivo = Venta::where('usuario_id', $cajaAbierta->usuario_id)
                ->where('metodo_pago', 'Efectivo')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('total');

            $ventasQr = Venta::where('usuario_id', $cajaAbierta->usuario_id)
                ->where('metodo_pago', 'QR')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('total');

            $egresos = $cajaAbierta->movimientos()
                ->where('tipo', 'EGRESO')
                ->sum('monto');

            $totalVendidoCobrado = $ventasEfectivo + $ventasQr;

            $efectivoEnCaja =
                $cajaAbierta->monto_inicial
                + $ventasEfectivo
                - $egresos;

            $saldoQr = $ventasQr;
        }

        foreach ($cajas as $caja) {
            $fechaInicio = $caja->fecha_apertura;
            $fechaFin = $caja->fecha_cierre ?? now();

            $caja->ventas_efectivo = Venta::where('usuario_id', $caja->usuario_id)
                ->where('metodo_pago', 'Efectivo')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('total');

            $caja->ventas_qr = Venta::where('usuario_id', $caja->usuario_id)
                ->where('metodo_pago', 'QR')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('total');

            $caja->egresos_calculados = $caja->movimientos
                ->where('tipo', 'EGRESO')
                ->sum('monto');

            $caja->total_vendido_cobrado =
                $caja->ventas_efectivo
                + $caja->ventas_qr;

            $caja->efectivo_en_caja =
                $caja->monto_inicial
                + $caja->ventas_efectivo
                - $caja->egresos_calculados;

            $caja->saldo_qr = $caja->ventas_qr;
        }

        return view('caja.index', compact(
            'cajaAbierta',
            'cajas',
            'ventasEfectivo',
            'ventasQr',
            'egresos',
            'totalVendidoCobrado',
            'efectivoEnCaja',
            'saldoQr'
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
            $fechaInicio = $caja->fecha_apertura;
            $fechaFin = now();

            $ventasEfectivo = Venta::where('usuario_id', $caja->usuario_id)
                ->where('metodo_pago', 'Efectivo')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('total');

            $ventasQr = Venta::where('usuario_id', $caja->usuario_id)
                ->where('metodo_pago', 'QR')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('total');

            $egresos = $caja->movimientos()
                ->where('tipo', 'EGRESO')
                ->sum('monto');

            $montoFinal =
                $caja->monto_inicial
                + $ventasEfectivo
                - $egresos;

            $caja->update([
                'fecha_cierre' => $fechaFin,
                'monto_final' => $montoFinal,
                'estado' => 'CERRADA',
            ]);
        });

        return redirect()
            ->route('caja.index')
            ->with('success', 'Caja cerrada correctamente.');
    }
}
