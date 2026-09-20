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
                                    Producto
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
                                        {{ $producto->stock }}
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

</x-app-layout>