<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reportes del Sistema
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- FILTRO POR FECHAS --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h3 class="text-lg font-bold mb-4">
                    Filtrar por fechas
                </h3>

                <form method="GET" action="{{ route('reportes.index') }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                        <div>
                            <label class="block mb-1 font-semibold">
                                Fecha inicio
                            </label>

                            <input
                                type="date"
                                name="fecha_inicio"
                                value="{{ $fechaInicio }}"
                                class="w-full border-gray-300 rounded-lg"
                            >
                        </div>

                        <div>
                            <label class="block mb-1 font-semibold">
                                Fecha fin
                            </label>

                            <input
                                type="date"
                                name="fecha_fin"
                                value="{{ $fechaFin }}"
                                class="w-full border-gray-300 rounded-lg"
                            >
                        </div>

                        <div class="flex gap-2">

                            <button
                                type="submit"
                                style="
                                    background-color:#2563eb;
                                    color:white;
                                    padding:10px 20px;
                                    border-radius:8px;
                                    border:none;
                                    font-weight:600;
                                    cursor:pointer;
                                "
                            >
                                Filtrar
                            </button>

                            <a
                                href="{{ route('reportes.index') }}"
                                style="
                                    background-color:#6b7280;
                                    color:white;
                                    padding:10px 20px;
                                    border-radius:8px;
                                    text-decoration:none;
                                    font-weight:600;
                                "
                            >
                                Limpiar
                            </a>

                        </div>

                    </div>

                </form>

            </div>

            {{-- RESUMEN PRINCIPAL --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">

                <div class="bg-white shadow-sm rounded-lg p-5">
                    <p class="text-gray-500">Total ventas</p>

                    <p class="text-3xl font-bold mt-2">
                        Bs {{ number_format($totalVentas, 2) }}
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        {{ $cantidadVentas }} ventas
                    </p>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-5">
                    <p class="text-gray-500">Total compras</p>

                    <p class="text-3xl font-bold mt-2">
                        Bs {{ number_format($totalCompras, 2) }}
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        {{ $cantidadCompras }} compras
                    </p>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-5">
                    <p class="text-gray-500">Ingresos de caja</p>

                    <p class="text-3xl font-bold mt-2" style="color:#16a34a;">
                        Bs {{ number_format($ingresosCaja, 2) }}
                    </p>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-5">
                    <p class="text-gray-500">Egresos de caja</p>

                    <p class="text-3xl font-bold mt-2" style="color:#dc2626;">
                        Bs {{ number_format($egresosCaja, 2) }}
                    </p>
                </div>

            </div>

            {{-- VENTAS POR MÉTODO DE PAGO --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h3 class="text-lg font-bold mb-4">
                    Ventas por método de pago
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <div class="border rounded-lg p-5">
                        <p class="text-gray-500">
                            Efectivo
                        </p>

                        <p class="text-2xl font-bold mt-2" style="color:#16a34a;">
                            Bs {{ number_format($ventasEfectivo, 2) }}
                        </p>
                    </div>

                    <div class="border rounded-lg p-5">
                        <p class="text-gray-500">
                            QR
                        </p>

                        <p class="text-2xl font-bold mt-2" style="color:#2563eb;">
                            Bs {{ number_format($ventasQR, 2) }}
                        </p>
                    </div>

                    <div class="border rounded-lg p-5">
                        <p class="text-gray-500">
                            Tarjeta
                        </p>

                        <p class="text-2xl font-bold mt-2" style="color:#7c3aed;">
                            Bs {{ number_format($ventasTarjeta, 2) }}
                        </p>
                    </div>

                </div>

            </div>

            {{-- PRODUCTOS MÁS VENDIDOS --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h3 class="text-lg font-bold mb-4">
                    Productos más vendidos
                </h3>

                <div class="overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3">Producto</th>
                                <th class="border p-3">Cantidad vendida</th>
                                <th class="border p-3">Total vendido</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($productosMasVendidos as $producto)

                                <tr>

                                    <td class="border p-3">
                                        {{ $producto->nombre }}
                                    </td>

                                    <td class="border p-3 text-center">
                                        {{ $producto->cantidad_vendida }}
                                    </td>

                                    <td class="border p-3 text-right">
                                        Bs {{ number_format($producto->total_vendido, 2) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="3"
                                        class="border p-5 text-center text-gray-500"
                                    >
                                        No hay ventas registradas.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            {{-- STOCK BAJO --}}
            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4">
                    Productos con stock bajo
                </h3>

                <div class="overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3">Código</th>
                                <th class="border p-3">Producto</th>
                                <th class="border p-3">Categoría</th>
                                <th class="border p-3">Stock</th>
                                <th class="border p-3">Stock mínimo</th>
                                <th class="border p-3">Estado</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($productosStockBajo as $producto)

                                <tr>

                                    <td class="border p-3">
                                        {{ $producto->codigo }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $producto->nombre }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $producto->categoria->nombre ?? '-' }}
                                    </td>

                                    <td class="border p-3 text-center">
                                        {{ $producto->stock }}
                                    </td>

                                    <td class="border p-3 text-center">
                                        {{ $producto->stock_minimo }}
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
                                                Stock bajo
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="6"
                                        class="border p-5 text-center text-gray-500"
                                    >
                                        No hay productos con stock bajo.
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