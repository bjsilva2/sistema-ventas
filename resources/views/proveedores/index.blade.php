<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Proveedores
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


            {{-- NUEVO PROVEEDOR --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h3 class="text-lg font-bold mb-5">
                    Nuevo Proveedor
                </h3>

                <form action="{{ route('proveedores.store') }}" method="POST">

                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block mb-1 font-semibold">
                                NIT
                            </label>

                            <input
                                type="text"
                                name="nit"
                                value="{{ old('nit') }}"
                                class="w-full border-gray-300 rounded-lg"
                                placeholder="Ejemplo: 12345678"
                            >
                        </div>

                        <div>
                            <label class="block mb-1 font-semibold">
                                Nombre
                            </label>

                            <input
                                type="text"
                                name="nombre"
                                value="{{ old('nombre') }}"
                                class="w-full border-gray-300 rounded-lg"
                                placeholder="Ejemplo: Distribuidora ABC"
                                required
                            >
                        </div>

                        <div>
                            <label class="block mb-1 font-semibold">
                                Teléfono
                            </label>

                            <input
                                type="text"
                                name="telefono"
                                value="{{ old('telefono') }}"
                                class="w-full border-gray-300 rounded-lg"
                                placeholder="Ejemplo: 70000000"
                            >
                        </div>

                        <div>
                            <label class="block mb-1 font-semibold">
                                Correo
                            </label>

                            <input
                                type="email"
                                name="correo"
                                value="{{ old('correo') }}"
                                class="w-full border-gray-300 rounded-lg"
                                placeholder="Ejemplo: proveedor@gmail.com"
                            >
                        </div>

                    </div>

                    <div class="mt-4">

                        <label class="block mb-1 font-semibold">
                            Dirección
                        </label>

                        <input
                            type="text"
                            name="direccion"
                            value="{{ old('direccion') }}"
                            class="w-full border-gray-300 rounded-lg"
                            placeholder="Dirección del proveedor"
                        >

                    </div>

                    <div class="mt-5">

                        <button
                            type="submit"
                            style="
                                background-color: #2563eb;
                                color: white;
                                padding: 10px 22px;
                                border-radius: 8px;
                                font-weight: 600;
                                border: none;
                                cursor: pointer;
                            "
                        >
                            Guardar Proveedor
                        </button>

                    </div>

                </form>

            </div>


            {{-- TABLA --}}
            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4">
                    Proveedores registrados
                </h3>

                <form method="GET" action="{{ route('proveedores.index') }}" class="mb-4">
                    <div class="flex gap-2">

                        <input
                            type="text"
                            name="buscar"
                            value="{{ request('buscar') }}"
                            placeholder="Buscar por nombre, NIT, teléfono o correo..."
                            class="w-full border-gray-300 rounded-lg"
                        >

                        <button
                            type="submit"
                            style="
                                background:#2563eb;
                                color:white;
                                padding:10px 20px;
                                border-radius:8px;
                                border:none;
                                font-weight:600;
                                cursor:pointer;
                            "
                        >
                            Buscar
                        </button>

                        @if(request('buscar'))
                            <a
                                href="{{ route('proveedores.index') }}"
                                style="
                                    background:#6b7280;
                                    color:white;
                                    padding:10px 20px;
                                    border-radius:8px;
                                    text-decoration:none;
                                "
                            >
                                Limpiar
                            </a>
                        @endif

                    </div>
                </form>

                <div class="overflow-x-auto">

                    <table class="w-full border-collapse text-sm">

                        <thead>

                            <tr class="bg-gray-100">
                                <th class="border p-3">ID</th>
                                <th class="border p-3">NIT</th>
                                <th class="border p-3">Nombre</th>
                                <th class="border p-3">Teléfono</th>
                                <th class="border p-3">Correo</th>
                                <th class="border p-3">Dirección</th>
                                <th class="border p-3">Estado</th>
                                <th class="border p-3">Acciones</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($proveedores as $proveedor)

                                <tr>

                                    <td class="border p-3 text-center">
                                        {{ $proveedor->id }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $proveedor->nit ?? '-' }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $proveedor->nombre }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $proveedor->telefono ?? '-' }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $proveedor->correo ?? '-' }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $proveedor->direccion ?? '-' }}
                                    </td>

                                    <td class="border p-3 text-center">

                                        @if($proveedor->estado)

                                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold">
                                                Activo
                                            </span>

                                        @else

                                            <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full font-semibold">
                                                Inactivo
                                            </span>

                                        @endif

                                    </td>

                                    <td class="border p-3">

                                        <div class="flex justify-center gap-2">

                                            <button
                                                type="button"
                                                onclick='abrirEditarProveedor(@json($proveedor))'
                                                style="
                                                    background-color: #f59e0b;
                                                    color: white;
                                                    padding: 8px 14px;
                                                    border-radius: 8px;
                                                    font-weight: 600;
                                                    border: none;
                                                    cursor: pointer;
                                                "
                                            >
                                                Editar
                                            </button>

                                            <form
                                                action="{{ route('proveedores.destroy', $proveedor) }}"
                                                method="POST"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                @if($proveedor->estado)

                                                    <button
                                                        type="submit"
                                                        style="
                                                            background-color: #dc2626;
                                                            color: white;
                                                            padding: 8px 14px;
                                                            border-radius: 8px;
                                                            font-weight: 600;
                                                            border: none;
                                                            cursor: pointer;
                                                        "
                                                    >
                                                        Desactivar
                                                    </button>

                                                @else

                                                    <button
                                                        type="submit"
                                                        style="
                                                            background-color: #16a34a;
                                                            color: white;
                                                            padding: 8px 14px;
                                                            border-radius: 8px;
                                                            font-weight: 600;
                                                            border: none;
                                                            cursor: pointer;
                                                        "
                                                    >
                                                        Activar
                                                    </button>

                                                @endif

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="8"
                                        class="border p-6 text-center text-gray-500"
                                    >
                                        No hay proveedores registrados.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-6">
                    {{ $proveedores->links() }}
                </div>

            </div>

        </div>
    </div>


    {{-- MODAL EDITAR --}}
    <div
        id="modalEditarProveedor"
        style="
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        "
    >

        <div
            style="
                background: white;
                width: 100%;
                max-width: 650px;
                padding: 24px;
                border-radius: 12px;
            "
        >

            <h3 class="text-xl font-bold mb-5">
                Editar Proveedor
            </h3>

            <form id="formEditarProveedor" method="POST">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block mb-1 font-semibold">
                            NIT
                        </label>

                        <input
                            id="editarNit"
                            name="nit"
                            type="text"
                            class="w-full border-gray-300 rounded-lg"
                        >
                    </div>

                    <div>
                        <label class="block mb-1 font-semibold">
                            Nombre
                        </label>

                        <input
                            id="editarNombre"
                            name="nombre"
                            type="text"
                            class="w-full border-gray-300 rounded-lg"
                            required
                        >
                    </div>

                    <div>
                        <label class="block mb-1 font-semibold">
                            Teléfono
                        </label>

                        <input
                            id="editarTelefono"
                            name="telefono"
                            type="text"
                            class="w-full border-gray-300 rounded-lg"
                        >
                    </div>

                    <div>
                        <label class="block mb-1 font-semibold">
                            Correo
                        </label>

                        <input
                            id="editarCorreo"
                            name="correo"
                            type="email"
                            class="w-full border-gray-300 rounded-lg"
                        >
                    </div>

                </div>

                <div class="mt-4">

                    <label class="block mb-1 font-semibold">
                        Dirección
                    </label>

                    <input
                        id="editarDireccion"
                        name="direccion"
                        type="text"
                        class="w-full border-gray-300 rounded-lg"
                    >

                </div>

                <div class="flex justify-end gap-2 mt-5">

                    <button
                        type="button"
                        onclick="cerrarEditarProveedor()"
                        style="
                            background-color: #6b7280;
                            color: white;
                            padding: 9px 18px;
                            border-radius: 8px;
                            border: none;
                            cursor: pointer;
                        "
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        style="
                            background-color: #2563eb;
                            color: white;
                            padding: 9px 18px;
                            border-radius: 8px;
                            border: none;
                            cursor: pointer;
                        "
                    >
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>

    </div>


    <script>

        function abrirEditarProveedor(proveedor)
        {
            document.getElementById('editarNit').value =
                proveedor.nit ?? '';

            document.getElementById('editarNombre').value =
                proveedor.nombre ?? '';

            document.getElementById('editarTelefono').value =
                proveedor.telefono ?? '';

            document.getElementById('editarCorreo').value =
                proveedor.correo ?? '';

            document.getElementById('editarDireccion').value =
                proveedor.direccion ?? '';

            document.getElementById('formEditarProveedor').action =
                '/proveedores/' + proveedor.id;

            document.getElementById('modalEditarProveedor').style.display =
                'flex';
        }

        function cerrarEditarProveedor()
        {
            document.getElementById('modalEditarProveedor').style.display =
                'none';
        }

    </script>

</x-app-layout>