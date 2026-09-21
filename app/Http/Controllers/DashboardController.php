<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Venta;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProductos = Producto::count();

        $totalClientes = Cliente::count();

        $totalVentas = Venta::count();

        $totalIngresos = Venta::sum('total');

        return view('dashboard', compact(
            'totalProductos',
            'totalClientes',
            'totalVentas',
            'totalIngresos'
        ));
    }
}