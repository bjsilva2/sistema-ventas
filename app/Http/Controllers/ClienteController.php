<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $clientes = Cliente::when($buscar, function ($query, $buscar) {
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
        return view('clientes.index', compact(
            'clientes',
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

        Cliente::create([
            'nit' => $request->nit,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'direccion' => $request->direccion,
            'estado' => true,
        ]);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nit' => 'nullable|string|max:50',
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente->update([
            'nit' => $request->nit,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'direccion' => $request->direccion,
        ]);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->update([
            'estado' => !$cliente->estado,
        ]);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Estado del cliente actualizado.');
    }
}