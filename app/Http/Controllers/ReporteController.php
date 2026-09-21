<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Compra;
use App\Models\Producto;
use App\Models\MovimientoCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $ventasQuery = Venta::query();

        $comprasQuery = Compra::query();

        if ($fechaInicio && $fechaFin) {
            $ventasQuery->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            $comprasQuery->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        }

        $totalVentas = $ventasQuery->sum('total');

        $cantidadVentas = $ventasQuery->count();

        $totalCompras = $comprasQuery->sum('total');

        $cantidadCompras = $comprasQuery->count();

        $ventasEfectivo = Venta::where('metodo_pago', 'Efectivo')
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            })
            ->sum('total');

        $ventasQR = Venta::where('metodo_pago', 'QR')
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            })
            ->sum('total');

        $ventasTarjeta = Venta::where('metodo_pago', 'Tarjeta')
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            })
            ->sum('total');

        $productosStockBajo = Producto::with('categoria')
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->orderBy('stock')
            ->get();

        $productosMasVendidos = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->select(
                'productos.nombre',
                DB::raw('SUM(detalle_ventas.cantidad) as cantidad_vendida'),
                DB::raw('SUM(detalle_ventas.subtotal) as total_vendido')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('cantidad_vendida')
            ->limit(10)
            ->get();

        $ingresosCaja = MovimientoCaja::where('tipo', 'INGRESO')->sum('monto');

        $egresosCaja = MovimientoCaja::where('tipo', 'EGRESO')->sum('monto');

        return view('reportes.index', compact(
            'fechaInicio',
            'fechaFin',
            'totalVentas',
            'cantidadVentas',
            'totalCompras',
            'cantidadCompras',
            'ventasEfectivo',
            'ventasQR',
            'ventasTarjeta',
            'productosStockBajo',
            'productosMasVendidos',
            'ingresosCaja',
            'egresosCaja'
        ));
    }
}