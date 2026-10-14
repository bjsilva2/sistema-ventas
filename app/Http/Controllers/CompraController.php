<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\PresentacionProducto;
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
                'detalles.producto',
                'detalles.presentacion'
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


        $productos = Producto::with([
                'presentaciones' => function ($query) {
                    $query->where('estado', true);
                }
            ])
            ->where('estado', true)
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

            'productos' =>
                'required|array|min:1',

            'productos.*.producto_id' =>
                'required|exists:productos,id',

            'productos.*.presentacion_id' =>
                'nullable|exists:presentaciones_producto,id',

            'productos.*.cantidad_presentaciones' =>
                'required|integer|min:1',

            'productos.*.precio_compra' =>
                'required|numeric|min:0',

            'productos.*.fecha_vencimiento' =>
                'nullable|date',

        ]);


        DB::beginTransaction();


        try {

            /*
            |--------------------------------------------------------------------------
            | GENERAR NÚMERO AUTOMÁTICO
            |--------------------------------------------------------------------------
            */

            $ultimoId =
                Compra::max('id') ?? 0;


            $numeroComprobante =
                'COM-' . str_pad(
                    $ultimoId + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                );


            /*
            |--------------------------------------------------------------------------
            | CREAR COMPRA
            |--------------------------------------------------------------------------
            */

            $compra = Compra::create([

                'proveedor_id' =>
                    $request->proveedor_id,

                'usuario_id' =>
                    auth()->id(),

                'fecha' =>
                    $request->fecha,

                'numero_comprobante' =>
                    $numeroComprobante,

                'total' =>
                    0,

                'estado' =>
                    true,

            ]);


            $totalCompra = 0;


            /*
            |--------------------------------------------------------------------------
            | REGISTRAR PRODUCTOS
            |--------------------------------------------------------------------------
            */

            foreach ($request->productos as $item) {

                $producto = Producto::lockForUpdate()
                    ->findOrFail(
                        $item['producto_id']
                    );


                /*
                |--------------------------------------------------------------------------
                | PRESENTACIÓN
                |--------------------------------------------------------------------------
                */

                $presentacion = null;

                $unidadesPorPresentacion = 1;


                if (!empty($item['presentacion_id'])) {

                    $presentacion = PresentacionProducto::where(
                            'producto_id',
                            $producto->id
                        )
                        ->where(
                            'id',
                            $item['presentacion_id']
                        )
                        ->where(
                            'estado',
                            true
                        )
                        ->firstOrFail();


                    $unidadesPorPresentacion =
                        (int) $presentacion->cantidad_unidades;
                }


                /*
                |--------------------------------------------------------------------------
                | CANTIDAD DE PAQUETES / PRESENTACIONES
                |--------------------------------------------------------------------------
                */

                $cantidadPresentaciones =
                    (int) $item['cantidad_presentaciones'];


                /*
                |--------------------------------------------------------------------------
                | CANTIDAD REAL DE UNIDADES
                |--------------------------------------------------------------------------
                */

                $cantidadUnidades =
                    $cantidadPresentaciones
                    * $unidadesPorPresentacion;


                /*
                |--------------------------------------------------------------------------
                | PRECIO POR PRESENTACIÓN
                |--------------------------------------------------------------------------
                */

                $precioCompra =
                    (float) $item['precio_compra'];


                /*
                |--------------------------------------------------------------------------
                | SUBTOTAL
                |--------------------------------------------------------------------------
                */

                $subtotal =
                    $cantidadPresentaciones
                    * $precioCompra;


                /*
                |--------------------------------------------------------------------------
                | GUARDAR DETALLE
                |--------------------------------------------------------------------------
                */

                DetalleCompra::create([

                    'compra_id' =>
                        $compra->id,

                    'producto_id' =>
                        $producto->id,

                    'presentacion_id' =>
                        $presentacion?->id,

                    'cantidad_presentaciones' =>
                        $cantidadPresentaciones,

                    'cantidad' =>
                        $cantidadUnidades,

                    'precio_compra' =>
                        $precioCompra,

                    'subtotal' =>
                        $subtotal,

                    'fecha_vencimiento' =>
                        $item['fecha_vencimiento'] ?? null,

                ]);


                /*
                |--------------------------------------------------------------------------
                | AUMENTAR STOCK EN UNIDADES REALES
                |--------------------------------------------------------------------------
                */

                $producto->increment(
                    'stock',
                    $cantidadUnidades
                );


                /*
                |--------------------------------------------------------------------------
                | ACTUALIZAR PRECIO BASE DE COMPRA
                |--------------------------------------------------------------------------
                */

                $precioCompraUnitario =
                    $unidadesPorPresentacion > 0
                        ? $precioCompra / $unidadesPorPresentacion
                        : $precioCompra;


                $producto->update([

                    'precio_compra' =>
                        $precioCompraUnitario,

                ]);


                $totalCompra +=
                    $subtotal;
            }


            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR TOTAL
            |--------------------------------------------------------------------------
            */

            $compra->update([

                'total' =>
                    $totalCompra

            ]);


            DB::commit();


            return redirect()
                ->route('compras.index')
                ->with(
                    'success',
                    'Compra '
                    . $compra->numero_comprobante
                    . ' registrada correctamente.'
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