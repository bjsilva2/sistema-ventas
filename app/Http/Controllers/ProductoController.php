<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $productos = Producto::with([
            'categoria',
            'presentaciones'
        ])
            ->when($buscar, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('codigo', 'ilike', "%{$buscar}%")
                      ->orWhere('nombre', 'ilike', "%{$buscar}%")
                      ->orWhere('descripcion', 'ilike', "%{$buscar}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $categorias = Categoria::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('productos.index', compact(
            'productos',
            'categorias',
            'buscar'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:255|unique:productos,codigo',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',

            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'categoria_id' => 'required|exists:categorias,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);


        $rutaImagen = null;


        // GUARDAR IMAGEN
        if ($request->hasFile('imagen')) {

            $rutaImagen = $request
                ->file('imagen')
                ->store('productos', 'public');
        }


        Producto::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,

            'imagen' => $rutaImagen,

            'categoria_id' => $request->categoria_id,
            'precio_compra' => $request->precio_compra,
            'precio_venta' => $request->precio_venta,
            'stock' => $request->stock,
            'stock_minimo' => $request->stock_minimo,
            'estado' => true,
        ]);


        return redirect()
            ->route('productos.index')
            ->with(
                'success',
                'Producto registrado correctamente.'
            );
    }


    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'codigo' =>
                'required|string|max:255|unique:productos,codigo,' . $producto->id,

            'nombre' =>
                'required|string|max:255',

            'descripcion' =>
                'nullable|string',

            'imagen' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'categoria_id' =>
                'required|exists:categorias,id',

            'precio_compra' =>
                'required|numeric|min:0',

            'precio_venta' =>
                'required|numeric|min:0',

            'stock' =>
                'required|integer|min:0',

            'stock_minimo' =>
                'required|integer|min:0',
        ]);


        // CONSERVAR IMAGEN ACTUAL
        $rutaImagen = $producto->imagen;


        // SI SUBE UNA NUEVA IMAGEN
        if ($request->hasFile('imagen')) {

            // ELIMINAR IMAGEN ANTERIOR
            if (
                $producto->imagen &&
                Storage::disk('public')->exists($producto->imagen)
            ) {
                Storage::disk('public')
                    ->delete($producto->imagen);
            }


            // GUARDAR NUEVA IMAGEN
            $rutaImagen = $request
                ->file('imagen')
                ->store('productos', 'public');
        }


        $producto->update([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,

            'imagen' => $rutaImagen,

            'categoria_id' => $request->categoria_id,
            'precio_compra' => $request->precio_compra,
            'precio_venta' => $request->precio_venta,
            'stock' => $request->stock,
            'stock_minimo' => $request->stock_minimo,
        ]);


        return redirect()
            ->route('productos.index')
            ->with(
                'success',
                'Producto actualizado correctamente.'
            );
    }


    public function destroy(Producto $producto)
    {
        $producto->update([
            'estado' => !$producto->estado,
        ]);


        return redirect()
            ->route('productos.index')
            ->with(
                'success',
                'Estado del producto actualizado.'
            );
    }
}