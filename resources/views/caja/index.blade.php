<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Caja
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

            @if(!$cajaAbierta)

                <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                    <h3 class="text-lg font-bold mb-4">
                        Abrir Caja
                    </h3>

                    <form action="{{ route('caja.abrir') }}" method="POST">

                        @csrf

                        <div class="max-w-md">

                            <label class="block mb-1 font-semibold">
                                Monto inicial
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="monto_inicial"
                                class="w-full border-gray-300 rounded-lg"
                                placeholder="0.00"
                                required
                            >

                        </div>

                        <div class="mt-4">

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
                                Abrir Caja
                            </button>

                        </div>

                    </form>

                </div>

            @else

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-gray-500">Monto inicial</p>
                        <p class="text-2xl font-bold mt-2">
                            Bs {{ number_format($cajaAbierta->monto_inicial, 2) }}
                        </p>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-gray-500">Ingresos</p>
                        <p class="text-2xl font-bold mt-2" style="color:#16a34a;">
                            Bs {{ number_format($ingresos, 2) }}
                        </p>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-gray-500">Egresos</p>
                        <p class="text-2xl font-bold mt-2" style="color:#dc2626;">
                            Bs {{ number_format($egresos, 2) }}
                        </p>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-gray-500">Saldo actual</p>
                        <p class="text-2xl font-bold mt-2">
                            Bs {{ number_format($saldoActual, 2) }}
                        </p>
                    </div>

                </div>

                <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                    <h3 class="text-lg font-bold mb-4">
                        Registrar Movimiento
                    </h3>

                    <form action="{{ route('caja.movimiento') }}" method="POST">

                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block mb-1 font-semibold">
                                    Tipo
                                </label>

                                <select
                                    name="tipo"
                                    class="w-full border-gray-300 rounded-lg"
                                    required
                                >
                                    <option value="">Seleccione</option>
                                    <option value="INGRESO">Ingreso</option>
                                    <option value="EGRESO">Egreso</option>
                                </select>
                            </div>

                            <div>
                                <label class="block mb-1 font-semibold">
                                    Concepto
                                </label>

                                <input
                                    type="text"
                                    name="concepto"
                                    class="w-full border-gray-300 rounded-lg"
                                    placeholder="Ejemplo: Pago de transporte"
                                    required
                                >
                            </div>

                            <div>
                                <label class="block mb-1 font-semibold">
                                    Monto
                                </label>

                                <input
                                    type="number"
                                    name="monto"
                                    step="0.01"
                                    min="0.01"
                                    class="w-full border-gray-300 rounded-lg"
                                    placeholder="0.00"
                                    required
                                >
                            </div>

                        </div>

                        <div class="mt-4">

                            <button
                                type="submit"
                                style="
                                    background-color:#16a34a;
                                    color:white;
                                    padding:10px 22px;
                                    border-radius:8px;
                                    border:none;
                                    font-weight:600;
                                    cursor:pointer;
                                "
                            >
                                Registrar Movimiento
                            </button>

                        </div>

                    </form>

                </div>

                <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                    <div class="flex items-center justify-between mb-4">

                        <h3 class="text-lg font-bold">
                            Movimientos de Caja
                        </h3>

                        <form action="{{ route('caja.cerrar') }}" method="POST">

                            @csrf

                            <button
                                type="submit"
                                style="
                                    background-color:#dc2626;
                                    color:white;
                                    padding:10px 22px;
                                    border-radius:8px;
                                    border:none;
                                    font-weight:600;
                                    cursor:pointer;
                                "
                            >
                                Cerrar Caja
                            </button>

                        </form>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full border-collapse">

                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border p-3">Fecha</th>
                                    <th class="border p-3">Tipo</th>
                                    <th class="border p-3">Concepto</th>
                                    <th class="border p-3">Monto</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($cajaAbierta->movimientos as $movimiento)

                                    <tr>

                                        <td class="border p-3">
                                            {{ $movimiento->fecha }}
                                        </td>

                                        <td class="border p-3 text-center">

                                            @if($movimiento->tipo === 'INGRESO')

                                                <span style="color:#16a34a;font-weight:600;">
                                                    INGRESO
                                                </span>

                                            @else

                                                <span style="color:#dc2626;font-weight:600;">
                                                    EGRESO
                                                </span>

                                            @endif

                                        </td>

                                        <td class="border p-3">
                                            {{ $movimiento->concepto }}
                                        </td>

                                        <td class="border p-3 text-right">
                                            Bs {{ number_format($movimiento->monto, 2) }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="4"
                                            class="border p-5 text-center text-gray-500"
                                        >
                                            No hay movimientos registrados.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            @endif


            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4">
                    Historial de Cajas
                </h3>

                <div class="overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3">ID</th>
                                <th class="border p-3">Usuario</th>
                                <th class="border p-3">Apertura</th>
                                <th class="border p-3">Cierre</th>
                                <th class="border p-3">Inicial</th>
                                <th class="border p-3">Final</th>
                                <th class="border p-3">Estado</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($cajas as $caja)

                                <tr>

                                    <td class="border p-3 text-center">
                                        {{ $caja->id }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $caja->usuario->name ?? '-' }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $caja->fecha_apertura }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $caja->fecha_cierre ?? '-' }}
                                    </td>

                                    <td class="border p-3 text-right">
                                        Bs {{ number_format($caja->monto_inicial, 2) }}
                                    </td>

                                    <td class="border p-3 text-right">
                                        @if($caja->monto_final !== null)
                                            Bs {{ number_format($caja->monto_final, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="border p-3 text-center">

                                        @if($caja->estado === 'ABIERTA')

                                            <span style="color:#16a34a;font-weight:600;">
                                                ABIERTA
                                            </span>

                                        @else

                                            <span style="color:#6b7280;font-weight:600;">
                                                CERRADA
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="7"
                                        class="border p-5 text-center text-gray-500"
                                    >
                                        No hay cajas registradas.
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