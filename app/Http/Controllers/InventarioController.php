<?php

namespace App\Http\Controllers;

use App\Models\Producto;

class InventarioController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')
            ->orderBy('nombre')
            ->get();

        $totalProductos = Producto::count();

        $stockBajo = Producto::whereColumn('stock', '<=', 'stock_minimo')
            ->where('stock', '>', 0)
            ->count();

        $agotados = Producto::where('stock', 0)->count();

        $totalUnidades = Producto::sum('stock');

        return view('inventario.index', compact(
            'productos',
            'totalProductos',
            'stockBajo',
            'agotados',
            'totalUnidades'
        ));
    }
}