<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::with([
            'proveedor',
            'usuario',
            'detalles.producto'
        ])
        ->orderBy('id', 'desc')
        ->get();

        $proveedores = Proveedor::where('estado', true)
            ->orderBy('nombre')
            ->get();

        $productos = Producto::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('compras.index', compact(
            'compras',
            'proveedores',
            'productos'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedors,id',
            'fecha' => 'required|date',
            'numero_comprobante' => 'nullable|string|max:255',

            'productos' => 'required|array|min:1',

            'productos.*.producto_id' =>
                'required|exists:productos,id',

            'productos.*.cantidad' =>
                'required|integer|min:1',

            'productos.*.precio_compra' =>
                'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {

            $compra = Compra::create([
                'proveedor_id' => $request->proveedor_id,

                'usuario_id' => auth()->id(),

                'fecha' => $request->fecha,

                'numero_comprobante' =>
                    $request->numero_comprobante,

                'total' => 0,

                'estado' => true,
            ]);

            $totalCompra = 0;

            foreach ($request->productos as $item) {

                $producto = Producto::findOrFail(
                    $item['producto_id']
                );

                $cantidad = (int) $item['cantidad'];

                $precioCompra =
                    (float) $item['precio_compra'];

                $subtotal =
                    $cantidad * $precioCompra;

                DetalleCompra::create([
                    'compra_id' => $compra->id,

                    'producto_id' => $producto->id,

                    'cantidad' => $cantidad,

                    'precio_compra' => $precioCompra,

                    'subtotal' => $subtotal,
                ]);

                // AUMENTAR STOCK
                $producto->increment(
                    'stock',
                    $cantidad
                );

                // ACTUALIZAR PRECIO DE COMPRA
                $producto->update([
                    'precio_compra' => $precioCompra,
                ]);

                $totalCompra += $subtotal;
            }

            $compra->update([
                'total' => $totalCompra
            ]);

            DB::commit();

            return redirect()
                ->route('compras.index')
                ->with(
                    'success',
                    'Compra registrada correctamente.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'error' =>
                        'No se pudo registrar la compra: '
                        . $e->getMessage()
                ]);
        }
    }
}