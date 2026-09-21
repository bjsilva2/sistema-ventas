<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $proveedores = Proveedor::when($buscar, function ($query, $buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'ilike', "%{$buscar}%")
                  ->orWhere('nit', 'ilike', "%{$buscar}%")
                  ->orWhere('telefono', 'ilike', "%{$buscar}%")
                  ->orWhere('correo', 'ilike', "%{$buscar}%");
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('proveedores.index', compact(
            'proveedores',
            'buscar'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nit' => 'nullable|string|max:50',
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        Proveedor::create([
            'nit' => $request->nit,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'direccion' => $request->direccion,
            'estado' => true,
        ]);

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor registrado correctamente.');
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nit' => 'nullable|string|max:50',
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        $proveedor->update([
            'nit' => $request->nit,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'direccion' => $request->direccion,
        ]);

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->update([
            'estado' => !$proveedor->estado,
        ]);

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Estado del proveedor actualizado.');
    }
}
