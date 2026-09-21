<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Usuarios
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- CREAR USUARIO --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h3 class="text-lg font-bold mb-5">
                    Nuevo Usuario
                </h3>

                <form action="{{ route('usuarios.store') }}" method="POST">

                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block mb-1 font-semibold">
                                Nombre
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="w-full border-gray-300 rounded-lg"
                                placeholder="Ejemplo: Juan Pérez"
                                required
                            >
                        </div>

                        <div>
                            <label class="block mb-1 font-semibold">
                                Correo
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="w-full border-gray-300 rounded-lg"
                                placeholder="usuario@sistema.com"
                                required
                            >
                        </div>

                        <div>
                            <label class="block mb-1 font-semibold">
                                Contraseña
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="w-full border-gray-300 rounded-lg"
                                required
                            >
                        </div>

                        <div>
                            <label class="block mb-1 font-semibold">
                                Confirmar contraseña
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="w-full border-gray-300 rounded-lg"
                                required
                            >
                        </div>

                        <div>
                            <label class="block mb-1 font-semibold">
                                Rol
                            </label>

                            <select
                                name="rol"
                                class="w-full border-gray-300 rounded-lg"
                                required
                            >
                                <option value="">
                                    Seleccione un rol
                                </option>

                                <option value="Administrador">
                                    Administrador
                                </option>

                                <option value="Vendedor">
                                    Vendedor
                                </option>

                                <option value="Almacen">
                                    Almacén
                                </option>
                            </select>
                        </div>

                    </div>

                    <div class="mt-5">

                        <button
                            type="submit"
                            style="
                                background-color:#2563eb;
                                color:white;
                                padding:10px 22px;
                                border-radius:8px;
                                border:none;
                                font-weight:600;
                                cursor:pointer;
                            "
                        >
                            Crear Usuario
                        </button>

                    </div>

                </form>

            </div>


            {{-- LISTA DE USUARIOS --}}
            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4">
                    Usuarios registrados
                </h3>

                <div class="overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>

                            <tr class="bg-gray-100">
                                <th class="border p-3">ID</th>
                                <th class="border p-3">Nombre</th>
                                <th class="border p-3">Correo</th>
                                <th class="border p-3">Rol</th>
                                <th class="border p-3">Acciones</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($usuarios as $usuario)

                                <tr>

                                    <td class="border p-3 text-center">
                                        {{ $usuario->id }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $usuario->name }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $usuario->email }}
                                    </td>

                                    <td class="border p-3 text-center">

                                        @if($usuario->rol === 'Administrador')

                                            <span
                                                style="
                                                    background:#dbeafe;
                                                    color:#1d4ed8;
                                                    padding:6px 12px;
                                                    border-radius:20px;
                                                    font-weight:600;
                                                "
                                            >
                                                Administrador
                                            </span>

                                        @elseif($usuario->rol === 'Vendedor')

                                            <span
                                                style="
                                                    background:#dcfce7;
                                                    color:#15803d;
                                                    padding:6px 12px;
                                                    border-radius:20px;
                                                    font-weight:600;
                                                "
                                            >
                                                Vendedor
                                            </span>

                                        @else

                                            <span
                                                style="
                                                    background:#fef3c7;
                                                    color:#b45309;
                                                    padding:6px 12px;
                                                    border-radius:20px;
                                                    font-weight:600;
                                                "
                                            >
                                                Almacén
                                            </span>

                                        @endif

                                    </td>

                                    <td class="border p-3 text-center">

                                        <button
                                            type="button"
                                            onclick='abrirEditarUsuario(@json($usuario))'
                                            style="
                                                background-color:#f59e0b;
                                                color:white;
                                                padding:8px 14px;
                                                border-radius:8px;
                                                border:none;
                                                font-weight:600;
                                                cursor:pointer;
                                            "
                                        >
                                            Editar
                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="5"
                                        class="border p-5 text-center text-gray-500"
                                    >
                                        No hay usuarios registrados.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>


    {{-- MODAL EDITAR --}}
    <div
        id="modalEditarUsuario"
        style="
            display:none;
            position:fixed;
            inset:0;
            background:rgba(0,0,0,0.5);
            align-items:center;
            justify-content:center;
            z-index:9999;
            padding:20px;
        "
    >

        <div
            style="
                background:white;
                width:100%;
                max-width:600px;
                padding:24px;
                border-radius:12px;
            "
        >

            <h3 class="text-xl font-bold mb-5">
                Editar Usuario
            </h3>

            <form id="formEditarUsuario" method="POST">

                @csrf
                @method('PUT')

                <div class="mb-4">

                    <label class="block mb-1 font-semibold">
                        Nombre
                    </label>

                    <input
                        type="text"
                        id="editarNombre"
                        name="name"
                        class="w-full border-gray-300 rounded-lg"
                        required
                    >

                </div>

                <div class="mb-4">

                    <label class="block mb-1 font-semibold">
                        Correo
                    </label>

                    <input
                        type="email"
                        id="editarCorreo"
                        name="email"
                        class="w-full border-gray-300 rounded-lg"
                        required
                    >

                </div>

                <div class="mb-4">

                    <label class="block mb-1 font-semibold">
                        Rol
                    </label>

                    <select
                        id="editarRol"
                        name="rol"
                        class="w-full border-gray-300 rounded-lg"
                        required
                    >

                        <option value="Administrador">
                            Administrador
                        </option>

                        <option value="Vendedor">
                            Vendedor
                        </option>

                        <option value="Almacen">
                            Almacén
                        </option>

                    </select>

                </div>


                <div class="mb-4">

                    <label class="block mb-1 font-semibold">
                        Nueva contraseña
                    </label>

                    <input
                        type="password"
                        id="editarPassword"
                        name="password"
                        class="w-full border-gray-300 rounded-lg"
                        placeholder="Dejar vacío para mantener la actual"
                    >

                    <p class="text-sm text-gray-500 mt-1">
                        Mínimo 8 caracteres. Si no deseas cambiarla, deja este campo vacío.
                    </p>

                </div>


                <div class="mb-4">

                    <label class="block mb-1 font-semibold">
                        Confirmar nueva contraseña
                    </label>

                    <input
                        type="password"
                        id="editarPasswordConfirmation"
                        name="password_confirmation"
                        class="w-full border-gray-300 rounded-lg"
                        placeholder="Repite la nueva contraseña"
                    >

                </div>

                <div class="flex justify-end gap-2 mt-5">

                    <button
                        type="button"
                        onclick="cerrarEditarUsuario()"
                        style="
                            background-color:#6b7280;
                            color:white;
                            padding:9px 18px;
                            border-radius:8px;
                            border:none;
                            cursor:pointer;
                        "
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        style="
                            background-color:#2563eb;
                            color:white;
                            padding:9px 18px;
                            border-radius:8px;
                            border:none;
                            cursor:pointer;
                        "
                    >
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>

    </div>


    <script>

        function abrirEditarUsuario(usuario)
        {
            document.getElementById('editarNombre').value =
                usuario.name ?? '';

            document.getElementById('editarCorreo').value =
                usuario.email ?? '';

            document.getElementById('editarRol').value =
                usuario.rol ?? 'Vendedor';

            document.getElementById('editarPassword').value = '';
            document.getElementById('editarPasswordConfirmation').value = '';

            document.getElementById('formEditarUsuario').action =
                '/usuarios/' + usuario.id;

            document.getElementById('modalEditarUsuario').style.display =
                'flex';
        }


        function cerrarEditarUsuario()
        {
            document.getElementById('modalEditarUsuario').style.display =
                'none';
        }

    </script>

</x-app-layout>