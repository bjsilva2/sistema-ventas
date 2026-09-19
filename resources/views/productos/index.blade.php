<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Productos
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


            {{-- NUEVO PRODUCTO --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h3 class="text-lg font-bold mb-5">
                    Nuevo Producto
                </h3>

                <form action="{{ route('productos.store') }}" method="POST">

                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- CÓDIGO --}}
                        <div>
                            <label class="block mb-1 font-semibold">
                                Código
                            </label>

                            <input
                                type="text"
                                name="codigo"
                                value="{{ old('codigo') }}"
                                class="w-full border-gray-300 rounded-lg"
                                placeholder="Ejemplo: PROD001"
                                required
                            >
                        </div>


                        {{-- NOMBRE --}}
                        <div>
                            <label class="block mb-1 font-semibold">
                                Nombre
                            </label>

                            <input
                                type="text"
                                name="nombre"
                                value="{{ old('nombre') }}"
                                class="w-full border-gray-300 rounded-lg"
                                placeholder="Ejemplo: Coca Cola 2L"
                                required
                            >
                        </div>


                        {{-- CATEGORÍA --}}
                        <div>
                            <label class="block mb-1 font-semibold">
                                Categoría
                            </label>

                            <select
                                name="categoria_id"
                                class="w-full border-gray-300 rounded-lg"
                                required
                            >

                                <option value="">
                                    Seleccione una categoría
                                </option>

                                @foreach($categorias as $categoria)

                                    <option
                                        value="{{ $categoria->id }}"
                                        {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}
                                    >
                                        {{ $categoria->nombre }}
                                    </option>

                                @endforeach

                            </select>
                        </div>


                        {{-- PRECIO COMPRA --}}
                        <div>
                            <label class="block mb-1 font-semibold">
                                Precio compra
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="precio_compra"
                                value="{{ old('precio_compra') }}"
                                class="w-full border-gray-300 rounded-lg"
                                placeholder="0.00"
                                required
                            >
                        </div>


                        {{-- PRECIO VENTA --}}
                        <div>
                            <label class="block mb-1 font-semibold">
                                Precio venta
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="precio_venta"
                                value="{{ old('precio_venta') }}"
                                class="w-full border-gray-300 rounded-lg"
                                placeholder="0.00"
                                required
                            >
                        </div>


                        {{-- STOCK --}}
                        <div>
                            <label class="block mb-1 font-semibold">
                                Stock
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="stock"
                                value="{{ old('stock', 0) }}"
                                class="w-full border-gray-300 rounded-lg"
                                required
                            >
                        </div>


                        {{-- STOCK MÍNIMO --}}
                        <div>
                            <label class="block mb-1 font-semibold">
                                Stock mínimo
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="stock_minimo"
                                value="{{ old('stock_minimo', 0) }}"
                                class="w-full border-gray-300 rounded-lg"
                                required
                            >
                        </div>

                    </div>


                    {{-- DESCRIPCIÓN --}}
                    <div class="mt-4">

                        <label class="block mb-1 font-semibold">
                            Descripción
                        </label>

                        <textarea
                            name="descripcion"
                            rows="3"
                            class="w-full border-gray-300 rounded-lg"
                            placeholder="Descripción del producto"
                        >{{ old('descripcion') }}</textarea>

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
                            Guardar Producto
                        </button>

                    </div>

                </form>

            </div>


            {{-- TABLA --}}
            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4">
                    Productos registrados
                </h3>

                <div class="overflow-x-auto">

                    <table class="w-full border-collapse text-sm">

                        <thead>

                            <tr class="bg-gray-100">

                                <th class="border p-3">ID</th>

                                <th class="border p-3">Código</th>

                                <th class="border p-3">Nombre</th>

                                <th class="border p-3">Categoría</th>

                                <th class="border p-3">Compra</th>

                                <th class="border p-3">Venta</th>

                                <th class="border p-3">Stock</th>

                                <th class="border p-3">Mínimo</th>

                                <th class="border p-3">Estado</th>

                                <th class="border p-3">Acciones</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($productos as $producto)

                                <tr>

                                    <td class="border p-3 text-center">
                                        {{ $producto->id }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $producto->codigo }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $producto->nombre }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                                    </td>

                                    <td class="border p-3 text-center">
                                        Bs {{ number_format($producto->precio_compra, 2) }}
                                    </td>

                                    <td class="border p-3 text-center">
                                        Bs {{ number_format($producto->precio_venta, 2) }}
                                    </td>

                                    <td class="border p-3 text-center">

                                        @if($producto->stock <= $producto->stock_minimo)

                                            <span class="text-red-600 font-bold">
                                                {{ $producto->stock }}
                                            </span>

                                        @else

                                            <span>
                                                {{ $producto->stock }}
                                            </span>

                                        @endif

                                    </td>

                                    <td class="border p-3 text-center">
                                        {{ $producto->stock_minimo }}
                                    </td>

                                    <td class="border p-3 text-center">

                                        @if($producto->estado)

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

                                            {{-- EDITAR --}}
                                            <button
                                                type="button"
                                                onclick='abrirEditarProducto(@json($producto))'
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


                                            {{-- ACTIVAR / DESACTIVAR --}}
                                            <form
                                                action="{{ route('productos.destroy', $producto) }}"
                                                method="POST"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                @if($producto->estado)

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
                                        colspan="10"
                                        class="border p-6 text-center text-gray-500"
                                    >
                                        No hay productos registrados.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    {{-- MODAL EDITAR PRODUCTO --}}
    <div
        id="modalEditarProducto"
        style="
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
            z-index: 9999;
            overflow-y: auto;
            padding: 20px;
        "
    >

        <div
            style="
                background: white;
                width: 100%;
                max-width: 700px;
                padding: 24px;
                border-radius: 12px;
            "
        >

            <h3 class="text-xl font-bold mb-5">
                Editar Producto
            </h3>


            <form id="formEditarProducto" method="POST">

                @csrf
                @method('PUT')


                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block mb-1 font-semibold">
                            Código
                        </label>

                        <input
                            id="editarCodigo"
                            name="codigo"
                            type="text"
                            class="w-full border-gray-300 rounded-lg"
                            required
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
                            Categoría
                        </label>

                        <select
                            id="editarCategoria"
                            name="categoria_id"
                            class="w-full border-gray-300 rounded-lg"
                            required
                        >

                            @foreach($categorias as $categoria)

                                <option value="{{ $categoria->id }}">
                                    {{ $categoria->nombre }}
                                </option>

                            @endforeach

                        </select>
                    </div>


                    <div>
                        <label class="block mb-1 font-semibold">
                            Precio compra
                        </label>

                        <input
                            id="editarPrecioCompra"
                            name="precio_compra"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full border-gray-300 rounded-lg"
                            required
                        >
                    </div>


                    <div>
                        <label class="block mb-1 font-semibold">
                            Precio venta
                        </label>

                        <input
                            id="editarPrecioVenta"
                            name="precio_venta"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full border-gray-300 rounded-lg"
                            required
                        >
                    </div>


                    <div>
                        <label class="block mb-1 font-semibold">
                            Stock
                        </label>

                        <input
                            id="editarStock"
                            name="stock"
                            type="number"
                            min="0"
                            class="w-full border-gray-300 rounded-lg"
                            required
                        >
                    </div>


                    <div>
                        <label class="block mb-1 font-semibold">
                            Stock mínimo
                        </label>

                        <input
                            id="editarStockMinimo"
                            name="stock_minimo"
                            type="number"
                            min="0"
                            class="w-full border-gray-300 rounded-lg"
                            required
                        >
                    </div>

                </div>


                <div class="mt-4">

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


                <div class="flex justify-end gap-2 mt-5">

                    <button
                        type="button"
                        onclick="cerrarEditarProducto()"
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

        function abrirEditarProducto(producto)
        {
            document.getElementById('editarCodigo').value =
                producto.codigo ?? '';

            document.getElementById('editarNombre').value =
                producto.nombre ?? '';

            document.getElementById('editarCategoria').value =
                producto.categoria_id ?? '';

            document.getElementById('editarPrecioCompra').value =
                producto.precio_compra ?? 0;

            document.getElementById('editarPrecioVenta').value =
                producto.precio_venta ?? 0;

            document.getElementById('editarStock').value =
                producto.stock ?? 0;

            document.getElementById('editarStockMinimo').value =
                producto.stock_minimo ?? 0;

            document.getElementById('editarDescripcion').value =
                producto.descripcion ?? '';

            document.getElementById('formEditarProducto').action =
                '/productos/' + producto.id;

            document.getElementById('modalEditarProducto').style.display =
                'flex';
        }


        function cerrarEditarProducto()
        {
            document.getElementById('modalEditarProducto').style.display =
                'none';
        }

    </script>

</x-app-layout>