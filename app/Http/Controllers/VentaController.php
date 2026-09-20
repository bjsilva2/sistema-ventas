<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
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

            foreach ($request->productos as $item) {

                $producto = Producto::lockForUpdate()
                    ->findOrFail($item['producto_id']);

                $cantidad = (int) $item['cantidad'];

                if ($producto->stock < $cantidad) {

                    throw new \Exception(
                        'Stock insuficiente para '
                        . $producto->nombre
                        . '. Disponible: '
                        . $producto->stock
                    );
                }

                $precioVenta = (float) $producto->precio_venta;

                $subtotal = $cantidad * $precioVenta;

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_venta' => $precioVenta,
                    'subtotal' => $subtotal,
                ]);

                // DESCONTAR STOCK
                $producto->decrement(
                    'stock',
                    $cantidad
                );

                $totalVenta += $subtotal;
            }

            $venta->update([
                'total' => $totalVenta
            ]);

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