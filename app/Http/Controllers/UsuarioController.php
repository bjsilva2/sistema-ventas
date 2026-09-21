<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('id', 'desc')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'rol' => [
                'required',
                Rule::in([
                    'Administrador',
                    'Vendedor',
                    'Almacen',
                ]),
            ],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($usuario->id),
            ],
            'rol' => [
                'required',
                Rule::in([
                    'Administrador',
                    'Vendedor',
                    'Almacen',
                ]),
            ],
        ]);

        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
            'rol' => $request->rol,
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
}