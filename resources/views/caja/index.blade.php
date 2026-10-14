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

                {{-- RESUMEN DE CAJA ABIERTA --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-gray-500">Monto inicial</p>
                        <p class="text-2xl font-bold mt-2">
                            Bs {{ number_format($cajaAbierta->monto_inicial, 2) }}
                        </p>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-gray-500">Ventas en efectivo</p>
                        <p class="text-2xl font-bold mt-2" style="color:#16a34a;">
                            Bs {{ number_format($ventasEfectivo, 2) }}
                        </p>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-gray-500">Ventas por QR</p>
                        <p class="text-2xl font-bold mt-2" style="color:#2563eb;">
                            Bs {{ number_format($ventasQr, 2) }}
                        </p>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-gray-500">Egresos</p>
                        <p class="text-2xl font-bold mt-2" style="color:#dc2626;">
                            Bs {{ number_format($egresos, 2) }}
                        </p>
                    </div>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-gray-500">Total vendido / cobrado</p>
                        <p class="text-2xl font-bold mt-2">
                            Bs {{ number_format($totalVendidoCobrado, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Efectivo + QR
                        </p>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-gray-500">Efectivo en caja</p>
                        <p class="text-2xl font-bold mt-2" style="color:#16a34a;">
                            Bs {{ number_format($efectivoEnCaja, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Inicial + ventas efectivo - egresos
                        </p>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-gray-500">Saldo QR</p>
                        <p class="text-2xl font-bold mt-2" style="color:#2563eb;">
                            Bs {{ number_format($saldoQr, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Cobrado por QR
                        </p>
                    </div>

                </div>


                {{-- REGISTRAR MOVIMIENTO --}}
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


                {{-- MOVIMIENTOS Y CIERRE --}}
                <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">

                        <div>
                            <h3 class="text-lg font-bold">
                                Movimientos de Caja
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Al cerrar: efectivo Bs {{ number_format($efectivoEnCaja, 2) }}
                                · QR Bs {{ number_format($saldoQr, 2) }}
                            </p>
                        </div>

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


            {{-- HISTORIAL --}}
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
                                <th class="border p-3">Efectivo final</th>
                                <th class="border p-3">QR</th>
                                <th class="border p-3">Estado</th>
                                <th class="border p-3">Acciones</th>
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
                                        Bs {{ number_format($caja->efectivo_en_caja, 2) }}
                                    </td>

                                    <td class="border p-3 text-right">
                                        Bs {{ number_format($caja->saldo_qr, 2) }}
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

                                    <td class="border p-3 text-center">

                                        <button
                                            type="button"
                                            onclick='abrirDetalleCaja(@json($caja))'
                                            style="
                                                background:#0f766e;
                                                color:white;
                                                padding:8px 14px;
                                                border-radius:8px;
                                                border:none;
                                                font-weight:600;
                                                cursor:pointer;
                                            "
                                        >
                                            Ver detalle
                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="9"
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


    {{-- MODAL DETALLE DE CAJA --}}
    <div
        id="modalDetalleCaja"
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
                max-width:900px;
                max-height:90vh;
                overflow-y:auto;
                padding:24px;
                border-radius:12px;
            "
        >

            <div class="flex justify-between items-center mb-5">

                <h3 class="text-xl font-bold">
                    Detalle de Caja
                </h3>

                <button
                    type="button"
                    onclick="cerrarDetalleCaja()"
                    style="
                        background:#6b7280;
                        color:white;
                        padding:8px 14px;
                        border-radius:8px;
                        border:none;
                        cursor:pointer;
                    "
                >
                    Cerrar
                </button>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-5">

                <p>
                    <strong>Caja N.º:</strong>
                    <span id="detalleCajaId"></span>
                </p>

                <p>
                    <strong>Usuario:</strong>
                    <span id="detalleCajaUsuario"></span>
                </p>

                <p>
                    <strong>Apertura:</strong>
                    <span id="detalleCajaApertura"></span>
                </p>

                <p>
                    <strong>Cierre:</strong>
                    <span id="detalleCajaCierre"></span>
                </p>

                <p>
                    <strong>Estado:</strong>
                    <span id="detalleCajaEstado"></span>
                </p>

            </div>


            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-6">

                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500">Monto inicial</p>
                    <p class="font-bold text-lg">
                        Bs <span id="detalleCajaInicial"></span>
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500">Ventas efectivo</p>
                    <p class="font-bold text-lg" style="color:#16a34a;">
                        Bs <span id="detalleCajaEfectivo"></span>
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500">Ventas QR</p>
                    <p class="font-bold text-lg" style="color:#2563eb;">
                        Bs <span id="detalleCajaQr"></span>
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500">Egresos</p>
                    <p class="font-bold text-lg" style="color:#dc2626;">
                        Bs <span id="detalleCajaEgresos"></span>
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500">Total vendido / cobrado</p>
                    <p class="font-bold text-lg">
                        Bs <span id="detalleCajaTotalVendido"></span>
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500">Efectivo en caja</p>
                    <p class="font-bold text-lg" style="color:#16a34a;">
                        Bs <span id="detalleCajaFinalEfectivo"></span>
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500">Saldo QR</p>
                    <p class="font-bold text-lg" style="color:#2563eb;">
                        Bs <span id="detalleCajaSaldoQr"></span>
                    </p>
                </div>

            </div>


            <h4 class="font-bold mb-3">
                Movimientos registrados
            </h4>

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

                    <tbody id="detalleCajaMovimientos"></tbody>

                </table>

            </div>

        </div>
    </div>


    <script>

        function dinero(valor)
        {
            return Number(valor ?? 0).toFixed(2);
        }


        function abrirDetalleCaja(caja)
        {
            document.getElementById('detalleCajaId').textContent =
                caja.id ?? '-';

            document.getElementById('detalleCajaUsuario').textContent =
                caja.usuario?.name ?? '-';

            document.getElementById('detalleCajaApertura').textContent =
                caja.fecha_apertura ?? '-';

            document.getElementById('detalleCajaCierre').textContent =
                caja.fecha_cierre ?? '-';

            document.getElementById('detalleCajaEstado').textContent =
                caja.estado ?? '-';

            document.getElementById('detalleCajaInicial').textContent =
                dinero(caja.monto_inicial);

            document.getElementById('detalleCajaEfectivo').textContent =
                dinero(caja.ventas_efectivo);

            document.getElementById('detalleCajaQr').textContent =
                dinero(caja.ventas_qr);

            document.getElementById('detalleCajaEgresos').textContent =
                dinero(caja.egresos_calculados);

            document.getElementById('detalleCajaTotalVendido').textContent =
                dinero(caja.total_vendido_cobrado);

            document.getElementById('detalleCajaFinalEfectivo').textContent =
                dinero(caja.efectivo_en_caja);

            document.getElementById('detalleCajaSaldoQr').textContent =
                dinero(caja.saldo_qr);


            const tbody =
                document.getElementById('detalleCajaMovimientos');

            tbody.innerHTML = '';


            if (!caja.movimientos || caja.movimientos.length === 0) {

                tbody.innerHTML = `
                    <tr>
                        <td
                            colspan="4"
                            class="border p-4 text-center text-gray-500"
                        >
                            No hay movimientos registrados.
                        </td>
                    </tr>
                `;

            } else {

                caja.movimientos.forEach(movimiento => {

                    const color =
                        movimiento.tipo === 'INGRESO'
                            ? '#16a34a'
                            : '#dc2626';

                    tbody.innerHTML += `
                        <tr>
                            <td class="border p-3">
                                ${movimiento.fecha ?? '-'}
                            </td>

                            <td class="border p-3 text-center">
                                <span style="color:${color};font-weight:600;">
                                    ${movimiento.tipo ?? '-'}
                                </span>
                            </td>

                            <td class="border p-3">
                                ${movimiento.concepto ?? '-'}
                            </td>

                            <td class="border p-3 text-right">
                                Bs ${dinero(movimiento.monto)}
                            </td>
                        </tr>
                    `;
                });
            }


            document.getElementById('modalDetalleCaja').style.display =
                'flex';
        }


        function cerrarDetalleCaja()
        {
            document.getElementById('modalDetalleCaja').style.display =
                'none';
        }

    </script>

</x-app-layout>
