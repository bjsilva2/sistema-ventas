<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Caja;
use App\Models\MovimientoCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with([
            'cliente',
            'usuario',
            'detalles.producto'
        ])
        ->orderBy('id', 'desc')
        ->get();

        $clientes = Cliente::where('estado', true)
            ->orderBy('nombre')
            ->get();

        $productos = Producto::where('estado', true)
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        return view('ventas.index', compact(
            'ventas',
            'clientes',
            'productos'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'fecha' => 'required|date',
            'metodo_pago' => 'required|string|max:50',
            'tipo_comprobante' => 'nullable|string|max:50',
            'numero_comprobante' => 'nullable|string|max:255',

            'productos' => 'required|array|min:1',

            'productos.*.producto_id' =>
                'required|exists:productos,id',

            'productos.*.cantidad' =>
                'required|integer|min:1',
        ]);


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | VERIFICAR CAJA SI EL PAGO ES EN EFECTIVO
            |--------------------------------------------------------------------------
            */

            $caja = null;

            if ($request->metodo_pago === 'Efectivo') {

                $caja = Caja::where('usuario_id', auth()->id())
                    ->where('estado', 'ABIERTA')
                    ->first();

                if (!$caja) {
                    throw new \Exception(
                        'Debes abrir una caja antes de registrar una venta en efectivo.'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | CREAR VENTA
            |--------------------------------------------------------------------------
            */

            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'usuario_id' => auth()->id(),
                'fecha' => $request->fecha,
                'metodo_pago' => $request->metodo_pago,
                'tipo_comprobante' => $request->tipo_comprobante,
                'numero_comprobante' => $request->numero_comprobante,
                'total' => 0,
                'estado' => true,
            ]);


            $totalVenta = 0;


            /*
            |--------------------------------------------------------------------------
            | REGISTRAR PRODUCTOS
            |--------------------------------------------------------------------------
            */

            foreach ($request->productos as $item) {

                $producto = Producto::lockForUpdate()
                    ->findOrFail($item['producto_id']);

                $cantidad = (int) $item['cantidad'];


                /*
                |--------------------------------------------------------------------------
                | VERIFICAR STOCK
                |--------------------------------------------------------------------------
                */

                if ($producto->stock < $cantidad) {

                    throw new \Exception(
                        'Stock insuficiente para '
                        . $producto->nombre
                        . '. Disponible: '
                        . $producto->stock
                    );
                }


                $precioVenta =
                    (float) $producto->precio_venta;

                $subtotal =
                    $cantidad * $precioVenta;


                /*
                |--------------------------------------------------------------------------
                | GUARDAR DETALLE
                |--------------------------------------------------------------------------
                */

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_venta' => $precioVenta,
                    'subtotal' => $subtotal,
                ]);


                /*
                |--------------------------------------------------------------------------
                | DESCONTAR STOCK
                |--------------------------------------------------------------------------
                */

                $producto->decrement(
                    'stock',
                    $cantidad
                );


                $totalVenta += $subtotal;
            }


            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR TOTAL DE VENTA
            |--------------------------------------------------------------------------
            */

            $venta->update([
                'total' => $totalVenta
            ]);


            /*
            |--------------------------------------------------------------------------
            | REGISTRAR INGRESO AUTOMÁTICO EN CAJA
            |--------------------------------------------------------------------------
            */

            if ($request->metodo_pago === 'Efectivo' && $caja) {

                MovimientoCaja::create([
                    'caja_id' => $caja->id,
                    'tipo' => 'INGRESO',
                    'concepto' => 'Venta N.º ' . $venta->id,
                    'monto' => $totalVenta,
                    'fecha' => now(),
                ]);
            }


            DB::commit();


            return redirect()
                ->route('ventas.index')
                ->with(
                    'success',
                    'Venta registrada correctamente.'
                );


        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage()
                ]);
        }
    }
}