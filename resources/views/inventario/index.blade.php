<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inventario
        </h2>
    </x-slot>

    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- RESUMEN --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">

                <div class="bg-white shadow-sm rounded-lg p-5">
                    <p class="text-gray-500">
                        Productos
                    </p>

                    <p class="text-3xl font-bold mt-2">
                        {{ $totalProductos }}
                    </p>
                </div>


                <div class="bg-white shadow-sm rounded-lg p-5">
                    <p class="text-gray-500">
                        Unidades en stock
                    </p>

                    <p class="text-3xl font-bold mt-2">
                        {{ $totalUnidades }}
                    </p>
                </div>


                <div class="bg-white shadow-sm rounded-lg p-5">

                    <p class="text-gray-500">
                        Stock bajo
                    </p>

                    <p
                        class="text-3xl font-bold mt-2"
                        style="color:#d97706;"
                    >
                        {{ $stockBajo }}
                    </p>

                </div>


                <div class="bg-white shadow-sm rounded-lg p-5">

                    <p class="text-gray-500">
                        Agotados
                    </p>

                    <p
                        class="text-3xl font-bold mt-2"
                        style="color:#dc2626;"
                    >
                        {{ $agotados }}
                    </p>

                </div>

            </div>


            {{-- TABLA INVENTARIO --}}
            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4">
                    Estado del Inventario
                </h3>


                {{-- FILTROS --}}
                <form
                    method="GET"
                    action="{{ route('inventario.index') }}"
                    class="mb-5"
                >

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                        {{-- BUSCAR --}}
                        <div>

                            <label class="block mb-1 font-semibold">
                                Buscar
                            </label>

                            <input
                                type="text"
                                name="buscar"
                                value="{{ request('buscar') }}"
                                placeholder="Código o nombre..."
                                class="w-full border-gray-300 rounded-lg"
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
                            >

                                <option value="">
                                    Todas
                                </option>

                                @foreach($categorias as $categoria)

                                    <option
                                        value="{{ $categoria->id }}"
                                        {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}
                                    >
                                        {{ $categoria->nombre }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- ESTADO STOCK --}}
                        <div>

                            <label class="block mb-1 font-semibold">
                                Estado de stock
                            </label>

                            <select
                                name="estado_stock"
                                class="w-full border-gray-300 rounded-lg"
                            >

                                <option value="">
                                    Todos
                                </option>

                                <option
                                    value="normal"
                                    {{ request('estado_stock') === 'normal' ? 'selected' : '' }}
                                >
                                    Normal
                                </option>

                                <option
                                    value="bajo"
                                    {{ request('estado_stock') === 'bajo' ? 'selected' : '' }}
                                >
                                    Stock bajo
                                </option>

                                <option
                                    value="agotado"
                                    {{ request('estado_stock') === 'agotado' ? 'selected' : '' }}
                                >
                                    Agotado
                                </option>

                            </select>

                        </div>


                        {{-- BOTONES --}}
                        <div class="flex items-end gap-2">

                            <button
                                type="submit"
                                style="
                                    background:#2563eb;
                                    color:white;
                                    padding:10px 18px;
                                    border-radius:8px;
                                    border:none;
                                    font-weight:600;
                                    cursor:pointer;
                                "
                            >
                                Filtrar
                            </button>


                            @if(
                                request('buscar') ||
                                request('categoria_id') ||
                                request('estado_stock')
                            )

                                <a
                                    href="{{ route('inventario.index') }}"
                                    style="
                                        background:#6b7280;
                                        color:white;
                                        padding:10px 18px;
                                        border-radius:8px;
                                        text-decoration:none;
                                    "
                                >
                                    Limpiar
                                </a>

                            @endif

                        </div>

                    </div>

                </form>


                <div class="overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>

                            <tr class="bg-gray-100">

                                <th class="border p-3">
                                    Código
                                </th>

                                <th class="border p-3">
                                    Producto
                                </th>

                                <th class="border p-3">
                                    Categoría
                                </th>

                                <th class="border p-3">
                                    Stock
                                </th>

                                <th class="border p-3">
                                    Stock mínimo
                                </th>

                                <th class="border p-3">
                                    Precio compra
                                </th>

                                <th class="border p-3">
                                    Precio venta
                                </th>

                                <th class="border p-3">
                                    Estado stock
                                </th>

                                <th class="border p-3">
                                    Estado producto
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($productos as $producto)

                                <tr>

                                    <td class="border p-3">
                                        {{ $producto->codigo }}
                                    </td>

                                    <td class="border p-3 font-semibold">
                                        {{ $producto->nombre }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                                    </td>

                                    <td class="border p-3 text-center font-bold">

                                        @if($producto->stock == 0)

                                            <span style="color:#dc2626;">
                                                {{ $producto->stock }}
                                            </span>

                                        @elseif($producto->stock <= $producto->stock_minimo)

                                            <span style="color:#d97706;">
                                                {{ $producto->stock }}
                                            </span>

                                        @else

                                            <span style="color:#16a34a;">
                                                {{ $producto->stock }}
                                            </span>

                                        @endif

                                    </td>

                                    <td class="border p-3 text-center">
                                        {{ $producto->stock_minimo }}
                                    </td>

                                    <td class="border p-3 text-right">
                                        Bs {{ number_format($producto->precio_compra, 2) }}
                                    </td>

                                    <td class="border p-3 text-right">
                                        Bs {{ number_format($producto->precio_venta, 2) }}
                                    </td>

                                    <td class="border p-3 text-center">

                                        @if($producto->stock == 0)

                                            <span
                                                style="
                                                    background:#fee2e2;
                                                    color:#b91c1c;
                                                    padding:6px 12px;
                                                    border-radius:20px;
                                                    font-weight:600;
                                                "
                                            >
                                                Agotado
                                            </span>

                                        @elseif($producto->stock <= $producto->stock_minimo)

                                            <span
                                                style="
                                                    background:#fef3c7;
                                                    color:#b45309;
                                                    padding:6px 12px;
                                                    border-radius:20px;
                                                    font-weight:600;
                                                "
                                            >
                                                Stock bajo
                                            </span>

                                        @else

                                            <span
                                                style="
                                                    background:#dcfce7;
                                                    color:#15803d;
                                                    padding:6px 12px;
                                                    border-radius:20px;
                                                    font-weight:600;
                                                "
                                            >
                                                Normal
                                            </span>

                                        @endif

                                    </td>


                                    <td class="border p-3 text-center">

                                        @if($producto->estado)

                                            <span
                                                style="
                                                    color:#16a34a;
                                                    font-weight:600;
                                                "
                                            >
                                                Activo
                                            </span>

                                        @else

                                            <span
                                                style="
                                                    color:#dc2626;
                                                    font-weight:600;
                                                "
                                            >
                                                Inactivo
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="9"
                                        class="border p-6 text-center text-gray-500"
                                    >
                                        No hay productos que coincidan con los filtros.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINACIÓN --}}
                <div class="mt-6">
                    {{ $productos->links() }}
                </div>

            </div>

        </div>

    </div>

</x-app-layout>