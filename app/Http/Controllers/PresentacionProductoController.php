<?php

namespace App\Http\Controllers;

use App\Models\PresentacionProducto;
use Illuminate\Http\Request;

class PresentacionProductoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'nombre' => 'required|string|max:255',
            'cantidad_unidades' => 'required|integer|min:1',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'nullable|numeric|min:0',
        ]);

        PresentacionProducto::create([
            'producto_id' => $request->producto_id,
            'nombre' => $request->nombre,
            'cantidad_unidades' => $request->cantidad_unidades,
            'precio_compra' => $request->precio_compra,
            'precio_venta' => $request->precio_venta,
            'estado' => true,
        ]);

        return back()->with(
            'success',
            'Presentación registrada correctamente.'
        );
    }

    public function destroy(PresentacionProducto $presentacion)
    {
        $presentacion->update([
            'estado' => !$presentacion->estado
        ]);

        return back()->with(
            'success',
            'Estado de la presentación actualizado.'
        );
    }
}