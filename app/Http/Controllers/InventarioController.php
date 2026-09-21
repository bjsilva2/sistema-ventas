<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $categoriaId = $request->categoria_id;
        $estadoStock = $request->estado_stock;

        $productos = Producto::with('categoria')
            ->when($buscar, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('codigo', 'ilike', "%{$buscar}%")
                      ->orWhere('nombre', 'ilike', "%{$buscar}%");
                });
            })
            ->when($categoriaId, function ($query, $categoriaId) {
                $query->where('categoria_id', $categoriaId);
            })
            ->when($estadoStock, function ($query, $estadoStock) {

                if ($estadoStock === 'normal') {
                    $query->whereColumn('stock', '>', 'stock_minimo');
                }

                if ($estadoStock === 'bajo') {
                    $query->whereColumn('stock', '<=', 'stock_minimo')
                          ->where('stock', '>', 0);
                }

                if ($estadoStock === 'agotado') {
                    $query->where('stock', 0);
                }

            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        $categorias = Categoria::where('estado', true)
            ->orderBy('nombre')
            ->get();

        $totalProductos = Producto::count();

        $stockBajo = Producto::whereColumn('stock', '<=', 'stock_minimo')
            ->where('stock', '>', 0)
            ->count();

        $agotados = Producto::where('stock', 0)
            ->count();

        $totalUnidades = Producto::sum('stock');

        return view('inventario.index', compact(
            'productos',
            'categorias',
            'buscar',
            'categoriaId',
            'estadoStock',
            'totalProductos',
            'stockBajo',
            'agotados',
            'totalUnidades'
        ));
    }
}