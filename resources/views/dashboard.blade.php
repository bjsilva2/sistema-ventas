<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Sistema de Ventas
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- TARJETAS DEL DASHBOARD --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- PRODUCTOS -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        <h3 class="text-lg font-bold text-gray-700">
                            Productos
                        </h3>

                        <p class="text-3xl font-bold mt-2">
                            {{ $totalProductos }}
                        </p>

                        <p class="text-gray-500">
                            Productos registrados
                        </p>

                    </div>
                </div>


                <!-- CLIENTES -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        <h3 class="text-lg font-bold text-gray-700">
                            Clientes
                        </h3>

                        <p class="text-3xl font-bold mt-2">
                            {{ $totalClientes }}
                        </p>

                        <p class="text-gray-500">
                            Clientes registrados
                        </p>

                    </div>
                </div>


                <!-- VENTAS -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        <h3 class="text-lg font-bold text-gray-700">
                            Ventas
                        </h3>

                        <p class="text-3xl font-bold mt-2">
                            {{ $totalVentas }}
                        </p>

                        <p class="text-gray-500">
                            Ventas realizadas
                        </p>

                    </div>
                </div>


                <!-- INGRESOS -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        <h3 class="text-lg font-bold text-gray-700">
                            Ingresos
                        </h3>

                        <p class="text-3xl font-bold mt-2">
                            Bs {{ number_format($totalIngresos, 2) }}
                        </p>

                        <p class="text-gray-500">
                            Total de ingresos
                        </p>

                    </div>
                </div>

            </div>


            {{-- MÓDULOS DEL SISTEMA --}}
            <div class="mt-8 bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <h3 class="text-xl font-bold mb-6">
                        Módulos del sistema
                    </h3>


                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">


                        {{-- ADMINISTRADOR + ALMACÉN --}}
                        @if(in_array(auth()->user()->rol, ['Administrador', 'Almacen']))

                            <!-- PRODUCTOS -->
                            <a href="{{ route('productos.index') }}"
                               class="p-5 bg-blue-100 hover:bg-blue-200 rounded-lg text-center font-semibold">
                                Productos
                            </a>


                            <!-- CATEGORÍAS -->
                            <a href="{{ route('categorias.index') }}"
                               class="p-5 bg-green-100 hover:bg-green-200 rounded-lg text-center font-semibold">
                                Categorías
                            </a>


                            <!-- PROVEEDORES -->
                            <a href="{{ route('proveedores.index') }}"
                               class="p-5 bg-purple-100 hover:bg-purple-200 rounded-lg text-center font-semibold">
                                Proveedores
                            </a>


                            <!-- COMPRAS -->
                            <a href="{{ route('compras.index') }}"
                               class="p-5 bg-orange-100 hover:bg-orange-200 rounded-lg text-center font-semibold">
                                Compras
                            </a>


                            <!-- INVENTARIO -->
                            <a href="{{ route('inventario.index') }}"
                               class="p-5 bg-indigo-100 hover:bg-indigo-200 rounded-lg text-center font-semibold">
                                Inventario
                            </a>

                        @endif



                        {{-- ADMINISTRADOR + VENDEDOR --}}
                        @if(in_array(auth()->user()->rol, ['Administrador', 'Vendedor']))

                            <!-- CLIENTES -->
                            <a href="{{ route('clientes.index') }}"
                               class="p-5 bg-yellow-100 hover:bg-yellow-200 rounded-lg text-center font-semibold">
                                Clientes
                            </a>


                            <!-- VENTAS -->
                            <a href="{{ route('ventas.index') }}"
                               class="p-5 bg-red-100 hover:bg-red-200 rounded-lg text-center font-semibold">
                                Ventas
                            </a>


                            <!-- CAJA -->
                            <a href="{{ route('caja.index') }}"
                               class="p-5 bg-teal-100 hover:bg-teal-200 rounded-lg text-center font-semibold">
                                Caja
                            </a>

                        @endif



                        {{-- SOLO ADMINISTRADOR --}}
                        @if(auth()->user()->rol === 'Administrador')

                            <!-- REPORTES -->
                            <a href="{{ route('reportes.index') }}"
                               class="p-5 bg-gray-100 hover:bg-gray-200 rounded-lg text-center font-semibold">
                                Reportes
                            </a>


                            <!-- USUARIOS -->
                            <a href="{{ route('usuarios.index') }}"
                               class="p-5 bg-slate-200 hover:bg-slate-300 rounded-lg text-center font-semibold">
                                Usuarios
                            </a>

                        @endif


                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>