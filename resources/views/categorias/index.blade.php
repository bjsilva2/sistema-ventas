<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Categorías
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- MENSAJE DE ÉXITO --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ERRORES --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- NUEVA CATEGORÍA --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h3 class="text-lg font-bold mb-4">
                    Nueva Categoría
                </h3>

                <form action="{{ route('categorias.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            value="{{ old('nombre') }}"
                            class="w-full border-gray-300 rounded-lg"
                            placeholder="Ejemplo: Bebidas"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">
                            Descripción
                        </label>

                        <textarea
                            name="descripcion"
                            rows="3"
                            class="w-full border-gray-300 rounded-lg"
                            placeholder="Ejemplo: Bebidas y productos líquidos"
                        >{{ old('descripcion') }}</textarea>
                    </div>

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
                        Guardar Categoría
                    </button>

                </form>

            </div>

            {{-- TABLA --}}
            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4">
                    Categorías registradas
                </h3>

                <div class="overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3">ID</th>
                                <th class="border p-3">Nombre</th>
                                <th class="border p-3">Descripción</th>
                                <th class="border p-3">Estado</th>
                                <th class="border p-3">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($categorias as $categoria)

                                <tr>

                                    <td class="border p-3 text-center">
                                        {{ $categoria->id }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $categoria->nombre }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $categoria->descripcion ?? 'Sin descripción' }}
                                    </td>

                                    <td class="border p-3 text-center">

                                        @if($categoria->estado)
                                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold">
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full font-semibold">
                                                Inactivo
                                            </span>
                                        @endif

                                    </td>

                                    <td class="border p-3 text-center">

                                        <div class="flex justify-center gap-2">

                                            {{-- EDITAR --}}
                                            <button
                                                type="button"
                                                onclick="abrirEditar(
                                                    {{ $categoria->id }},
                                                    '{{ addslashes($categoria->nombre) }}',
                                                    '{{ addslashes($categoria->descripcion ?? '') }}'
                                                )"
                                                style="
                                                    background-color: #f59e0b;
                                                    color: white;
                                                    padding: 8px 16px;
                                                    border-radius: 8px;
                                                    font-weight: 600;
                                                    border: none;
                                                    cursor: pointer;
                                                "
                                            >
                                                Editar
                                            </button>

                                            {{-- ACTIVAR / DESACTIVAR --}}
                                            <form
                                                action="{{ route('categorias.destroy', $categoria) }}"
                                                method="POST"
                                                class="inline-block"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                @if($categoria->estado)

                                                    <button
                                                        type="submit"
                                                        style="
                                                            background-color: #dc2626;
                                                            color: white;
                                                            padding: 8px 16px;
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
                                                            padding: 8px 16px;
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
                                        colspan="5"
                                        class="border p-6 text-center text-gray-500"
                                    >
                                        No hay categorías registradas.
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
        id="modalEditar"
        style="
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
            z-index: 9999;
        "
    >

        <div
            style="
                background: white;
                width: 90%;
                max-width: 500px;
                padding: 24px;
                border-radius: 12px;
            "
        >

            <h3 class="text-xl font-bold mb-4">
                Editar Categoría
            </h3>

            <form id="formEditar" method="POST">

                @csrf
                @method('PUT')

                <div class="mb-4">

                    <label class="block mb-1 font-semibold">
                        Nombre
                    </label>

                    <input
                        type="text"
                        id="editarNombre"
                        name="nombre"
                        class="w-full border-gray-300 rounded-lg"
                        required
                    >

                </div>

                <div class="mb-4">

                    <label class="block mb-1 font-semibold">
                        Descripción
                    </label>

                    <textarea
                        id="editarDescripcion"
                        name="descripcion"
                        rows="3"
                        class="w-full border-gray-300 rounded-lg"
                    ></textarea>

                </div>

                <div class="flex justify-end gap-2">

                    <button
                        type="button"
                        onclick="cerrarEditar()"
                        style="
                            background-color: #6b7280;
                            color: white;
                            padding: 8px 16px;
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
                            padding: 8px 16px;
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
        function abrirEditar(id, nombre, descripcion) {

            const modal = document.getElementById('modalEditar');
            const form = document.getElementById('formEditar');

            document.getElementById('editarNombre').value = nombre;
            document.getElementById('editarDescripcion').value = descripcion;

            form.action = '/categorias/' + id;

            modal.style.display = 'flex';
        }

        function cerrarEditar() {
            document.getElementById('modalEditar').style.display = 'none';
        }
    </script>

</x-app-layout>