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
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $compras = Compra::with([
                'proveedor',
                'usuario',
                'detalles.producto'
            ])
            ->when($buscar, function ($query, $buscar) {

                $query->where(function ($q) use ($buscar) {

                    $q->where(
                        'numero_comprobante',
                        'ilike',
                        "%{$buscar}%"
                    )
                    ->orWhereHas(
                        'proveedor',
                        function ($proveedorQuery) use ($buscar) {

                            $proveedorQuery->where(
                                'nombre',
                                'ilike',
                                "%{$buscar}%"
                            );
                        }
                    );

                });

            })
            ->when($fechaInicio, function ($query, $fechaInicio) {

                $query->whereDate(
                    'fecha',
                    '>=',
                    $fechaInicio
                );

            })
            ->when($fechaFin, function ($query, $fechaFin) {

                $query->whereDate(
                    'fecha',
                    '<=',
                    $fechaFin
                );

            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();


        $proveedores = Proveedor::where('estado', true)
            ->orderBy('nombre')
            ->get();


        $productos = Producto::where('estado', true)
            ->orderBy('nombre')
            ->get();


        return view(
            'compras.index',
            compact(
                'compras',
                'proveedores',
                'productos',
                'buscar',
                'fechaInicio',
                'fechaFin'
            )
        );
    }


    public function store(Request $request)
    {
        $request->validate([

            'proveedor_id' =>
                'required|exists:proveedors,id',

            'fecha' =>
                'required|date',

            'numero_comprobante' =>
                'nullable|string|max:255',

            'productos' =>
                'required|array|min:1',

            'productos.*.producto_id' =>
                'required|exists:productos,id',

            'productos.*.cantidad' =>
                'required|integer|min:1',

            'productos.*.precio_compra' =>
                'required|numeric|min:0',

        ]);


        DB::beginTransaction();


        try {

            // CREAR COMPRA
            $compra = Compra::create([

                'proveedor_id' =>
                    $request->proveedor_id,

                'usuario_id' =>
                    auth()->id(),

                'fecha' =>
                    $request->fecha,

                'numero_comprobante' =>
                    $request->numero_comprobante,

                'total' =>
                    0,

                'estado' =>
                    true,

            ]);


            $totalCompra = 0;


            foreach ($request->productos as $item) {

                // BLOQUEA EL PRODUCTO DURANTE LA COMPRA
                $producto = Producto::lockForUpdate()
                    ->findOrFail(
                        $item['producto_id']
                    );


                $cantidad =
                    (int) $item['cantidad'];


                $precioCompra =
                    (float) $item['precio_compra'];


                $subtotal =
                    $cantidad * $precioCompra;


                // CREAR DETALLE
                DetalleCompra::create([

                    'compra_id' =>
                        $compra->id,

                    'producto_id' =>
                        $producto->id,

                    'cantidad' =>
                        $cantidad,

                    'precio_compra' =>
                        $precioCompra,

                    'subtotal' =>
                        $subtotal,

                ]);


                // AUMENTAR STOCK
                $producto->increment(
                    'stock',
                    $cantidad
                );


                // ACTUALIZAR PRECIO DE COMPRA
                $producto->update([

                    'precio_compra' =>
                        $precioCompra,

                ]);


                $totalCompra +=
                    $subtotal;
            }


            // ACTUALIZAR TOTAL
            $compra->update([

                'total' =>
                    $totalCompra

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