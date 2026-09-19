<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Sistema de Ventas
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- PRODUCTOS -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-700">
                            Productos
                        </h3>

                        <p class="text-3xl font-bold mt-2">
                            0
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
                            0
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
                            0
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
                            Bs 0.00
                        </p>

                        <p class="text-gray-500">
                            Total de ingresos
                        </p>
                    </div>
                </div>

            </div>

            <div class="mt-8 bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h3 class="text-xl font-bold mb-6">
                        Módulos del sistema
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

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

                        <!-- CLIENTES -->
                        <a href="#"
                           class="p-5 bg-yellow-100 hover:bg-yellow-200 rounded-lg text-center font-semibold">
                            Clientes
                        </a>

                        <!-- PROVEEDORES -->
                        <a href="#"
                           class="p-5 bg-purple-100 hover:bg-purple-200 rounded-lg text-center font-semibold">
                            Proveedores
                        </a>

                        <!-- COMPRAS -->
                        <a href="#"
                           class="p-5 bg-orange-100 hover:bg-orange-200 rounded-lg text-center font-semibold">
                            Compras
                        </a>

                        <!-- VENTAS -->
                        <a href="#"
                           class="p-5 bg-red-100 hover:bg-red-200 rounded-lg text-center font-semibold">
                            Ventas
                        </a>

                        <!-- INVENTARIO -->
                        <a href="#"
                           class="p-5 bg-indigo-100 hover:bg-indigo-200 rounded-lg text-center font-semibold">
                            Inventario
                        </a>

                        <!-- REPORTES -->
                        <a href="#"
                           class="p-5 bg-gray-100 hover:bg-gray-200 rounded-lg text-center font-semibold">
                            Reportes
                        </a>

                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>