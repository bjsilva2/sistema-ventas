<x-app-layout>



    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Gestión de Ventas

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



            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">



                <h3 class="text-lg font-bold mb-5">

                    Nueva Venta

                </h3>



                <form action="{{ route('ventas.store') }}" method="POST" id="formVenta">



                    @csrf



                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">



                        <div>

                            <label class="block mb-1 font-semibold">

                                Cliente

                            </label>



                            <select

                                name="cliente_id"

                                class="w-full border-gray-300 rounded-lg"

                            >

                                <option value="">

                                    Consumidor final

                                </option>



                                @foreach($clientes as $cliente)

                                    <option value="{{ $cliente->id }}">

                                        {{ $cliente->nombre }}

                                    </option>

                                @endforeach

                            </select>

                        </div>



                        <div>

                            <label class="block mb-1 font-semibold">

                                Fecha

                            </label>



                            <input

                                type="date"

                                name="fecha"

                                value="{{ date('Y-m-d') }}"

                                class="w-full border-gray-300 rounded-lg"

                                required

                            >

                        </div>



                        <div>

                            <label class="block mb-1 font-semibold">

                                Método de pago

                            </label>



                            <select

                                name="metodo_pago"

                                class="w-full border-gray-300 rounded-lg"

                                required

                            >

                                <option value="Efectivo" selected>

                                    Efectivo

                                </option>



                                <option value="QR">

                                    QR

                                </option>



                                <option value="Tarjeta">

                                    Tarjeta

                                </option>

                            </select>

                        </div>



                        <div>

                            <label class="block mb-1 font-semibold">

                                Tipo comprobante

                            </label>



                            <select

                                name="tipo_comprobante"

                                class="w-full border-gray-300 rounded-lg"

                            >

                                <option value="">

                                    Sin comprobante

                                </option>



                                <option value="Factura">

                                    Factura

                                </option>



                                <option value="Recibo">

                                    Recibo

                                </option>

                            </select>

                        </div>



                    </div>



                    <div class="mt-4" style="display:none;">



                        <label class="block mb-1 font-semibold">

                            Número de comprobante

                        </label>



                        <input

                            type="text"

                            name="numero_comprobante"

                            class="w-full border-gray-300 rounded-lg"

                            placeholder="Ejemplo: VEN-001"

                        >



                    </div>



                    <hr class="my-6">



                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">



                        <h4 class="font-bold">

                            Productos de la venta

                        </h4>



                        <div class="w-full md:w-96">

                            <input

                                type="text"

                                id="buscarProductoVenta"

                                placeholder="Buscar producto por código o nombre..."

                                class="w-full border-gray-300 rounded-lg"

                                oninput="filtrarTarjetasProductos()"

                            >

                        </div>



                    </div>



                    {{-- TARJETAS DE PRODUCTOS --}}

                    <div

                        id="contenedorTarjetasProductos"

                        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 mb-5"

                    >



                        @foreach($productos as $producto)



                            <button

                                type="button"

                                class="tarjeta-producto text-left bg-white border border-gray-200 rounded-xl p-3 hover:shadow-md hover:border-blue-400 transition"

                                data-id="{{ $producto->id }}"

                                data-codigo="{{ strtolower($producto->codigo) }}"

                                data-nombre="{{ $producto->nombre }}"

                                data-busqueda="{{ strtolower($producto->nombre) }}"

                                data-precio="{{ $producto->precio_venta }}"

                                data-stock="{{ $producto->stock }}"
                                data-presentaciones='@json($producto->presentaciones)' 

                                onclick="seleccionarProductoVenta(this)"

                            >



                                <div

                                    style="

                                        width:100%;

                                        height:110px;

                                        background:#f8fafc;

                                        border-radius:10px;

                                        display:flex;

                                        align-items:center;

                                        justify-content:center;

                                        overflow:hidden;

                                        margin-bottom:8px;

                                    "

                                >

                                    @if($producto->imagen)



                                        <img

                                            src="{{ asset('storage/' . $producto->imagen) }}"

                                            alt="{{ $producto->nombre }}"

                                            style="

                                                width:100%;

                                                height:100%;

                                                object-fit:contain;

                                            "

                                        >



                                    @else



                                        <div

                                            style="

                                                color:#9ca3af;

                                                font-size:13px;

                                                text-align:center;

                                                padding:10px;

                                            "

                                        >

                                            Sin imagen

                                        </div>



                                    @endif

                                </div>



                                <div class="font-semibold text-sm leading-tight">

                                    {{ $producto->nombre }}

                                </div>



                                <div class="text-xs text-gray-500 mt-1">

                                    {{ $producto->codigo }}

                                </div>



                                <div class="font-bold mt-2" style="color:#2563eb;">

                                    Bs {{ number_format($producto->precio_venta, 2) }}

                                </div>



                                <div class="text-xs mt-1" style="color:#16a34a;">

                                    Stock: {{ $producto->stock }}

                                </div>



                            </button>



                        @endforeach



                    </div>



                    <div class="mt-6 overflow-x-auto">



                        <table class="w-full border-collapse">



                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border p-3">Producto</th>
                                    <th class="border p-3">Presentación</th>
                                    <th class="border p-3">Cantidad</th>
                                    <th class="border p-3">Unidades reales</th>
                                    <th class="border p-3">Precio</th>
                                    <th class="border p-3">Subtotal</th>
                                    <th class="border p-3">Acción</th>
                                </tr>
                            </thead>



                            <tbody id="detalleVenta">



                                <tr>

                                    <td

                                        colspan="7"

                                        class="border p-4 text-center text-gray-500"

                                    >

                                        No hay productos agregados.

                                    </td>

                                </tr>



                            </tbody>



                        </table>



                    </div>



                    <div class="mt-5 text-right">



                        <div class="text-xl font-bold">

                            Total: Bs

                            <span id="totalVenta">0.00</span>

                        </div>



                    </div>



                    <div class="mt-5">



                        <button

                            type="submit"

                            style="

                                background-color:#2563eb;

                                color:white;

                                padding:10px 22px;

                                border-radius:8px;

                                font-weight:600;

                                border:none;

                                cursor:pointer;

                            "

                        >

                            Registrar Venta

                        </button>



                    </div>



                </form>



            </div>



            <div class="bg-white shadow-sm rounded-lg p-6">



                <h3 class="text-lg font-bold mb-4">

                    Ventas registradas

                </h3>



                <form method="GET" action="{{ route('ventas.index') }}" class="mb-5">

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3">



                        <div>

                            <label class="block mb-1 font-semibold">Buscar</label>

                            <input

                                type="text"

                                name="buscar"

                                value="{{ request('buscar') }}"

                                placeholder="Cliente o comprobante..."

                                class="w-full border-gray-300 rounded-lg"

                            >

                        </div>



                        <div>

                            <label class="block mb-1 font-semibold">Desde</label>

                            <input

                                type="date"

                                name="fecha_inicio"

                                value="{{ request('fecha_inicio') }}"

                                class="w-full border-gray-300 rounded-lg"

                            >

                        </div>



                        <div>

                            <label class="block mb-1 font-semibold">Hasta</label>

                            <input

                                type="date"

                                name="fecha_fin"

                                value="{{ request('fecha_fin') }}"

                                class="w-full border-gray-300 rounded-lg"

                            >

                        </div>



                        <div>

                            <label class="block mb-1 font-semibold">Método de pago</label>

                            <select

                                name="metodo_pago"

                                class="w-full border-gray-300 rounded-lg"

                            >

                                <option value="">Todos</option>

                                <option value="Efectivo" {{ request('metodo_pago') === 'Efectivo' ? 'selected' : '' }}>

                                    Efectivo

                                </option>

                                <option value="QR" {{ request('metodo_pago') === 'QR' ? 'selected' : '' }}>

                                    QR

                                </option>

                                <option value="Tarjeta" {{ request('metodo_pago') === 'Tarjeta' ? 'selected' : '' }}>

                                    Tarjeta

                                </option>

                            </select>

                        </div>



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



                            @if(request('buscar') || request('fecha_inicio') || request('fecha_fin') || request('metodo_pago'))

                                <a

                                    href="{{ route('ventas.index') }}"

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

                                <th class="border p-3">ID</th>

                                <th class="border p-3">Fecha</th>

                                <th class="border p-3">Cliente</th>

                                <th class="border p-3">Pago</th>

                                <th class="border p-3">Comprobante</th>

                                <th class="border p-3">Usuario</th>

                                <th class="border p-3">Total</th>

                                <th class="border p-3">Acciones</th>

                            </tr>



                        </thead>



                        <tbody>



                            @forelse($ventas as $venta)



                                <tr>



                                    <td class="border p-3 text-center">

                                        {{ $venta->id }}

                                    </td>



                                    <td class="border p-3">

                                        {{ $venta->fecha }}

                                    </td>



                                    <td class="border p-3">

                                        {{ $venta->cliente->nombre ?? 'Consumidor final' }}

                                    </td>



                                    <td class="border p-3">

                                        {{ $venta->metodo_pago }}

                                    </td>



                                    <td class="border p-3">

                                        <div class="font-semibold">

                                            {{ $venta->numero_comprobante ?? '-' }}

                                        </div>

                                        <div class="text-xs text-gray-500 mt-1">

                                            {{ $venta->tipo_comprobante ?? 'Sin comprobante' }}

                                        </div>

                                    </td>



                                    <td class="border p-3">

                                        {{ $venta->usuario->name ?? '-' }}

                                    </td>



                                    <td class="border p-3 text-right">

                                        Bs {{ number_format($venta->total, 2) }}

                                    </td>



                                    <td class="border p-3 text-center">

                                        <button

                                            type="button"

                                            onclick='abrirDetalleVenta(@json($venta))'

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

                                        colspan="8"

                                        class="border p-5 text-center text-gray-500"

                                    >

                                        No hay ventas registradas.

                                    </td>



                                </tr>



                            @endforelse



                        </tbody>



                    </table>



                </div>



                <div class="mt-6">

                    {{ $ventas->links() }}

                </div>



            </div>



        </div>

    </div>






    {{-- MODAL SELECCIONAR PRESENTACIÓN --}}
    <div
        id="modalSeleccionPresentacion"
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
                max-width:520px;
                padding:24px;
                border-radius:12px;
            "
        >
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h3 class="text-xl font-bold">Agregar producto</h3>
                    <p class="text-sm text-gray-500 mt-1" id="productoSeleccionadoNombre"></p>
                </div>

                <button
                    type="button"
                    onclick="cerrarSeleccionPresentacion()"
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

            <div class="grid grid-cols-1 gap-4">

                <div>
                    <label class="block mb-1 font-semibold">
                        Presentación
                    </label>

                    <select
                        id="ventaPresentacionSelect"
                        class="w-full border-gray-300 rounded-lg"
                        onchange="actualizarPrecioPresentacionVenta()"
                    >
                        <option value="">Unidad</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-semibold">
                        Cantidad
                    </label>

                    <input
                        type="number"
                        id="ventaCantidadPresentacion"
                        min="1"
                        value="1"
                        class="w-full border-gray-300 rounded-lg"
                    >
                </div>

                <div>
                    <label class="block mb-1 font-semibold">
                        Precio de venta
                    </label>

                    <input
                        type="number"
                        id="ventaPrecioPresentacion"
                        min="0"
                        step="0.01"
                        class="w-full border-gray-300 rounded-lg bg-gray-100"
                        readonly
                    >
                </div>

                <div class="text-sm text-gray-600">
                    Stock disponible:
                    <strong id="ventaStockDisponible">0</strong>
                    unidades
                </div>

                <button
                    type="button"
                    onclick="confirmarProductoVenta()"
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
                    Agregar a la venta
                </button>

            </div>
        </div>
    </div>

    {{-- MODAL DETALLE DE VENTA --}}

    <div

        id="modalDetalleVenta"

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

                    Detalle de Venta

                </h3>



                <button

                    type="button"

                    onclick="cerrarDetalleVenta()"

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

                <p><strong>Número de venta:</strong> <span id="detalleVentaId"></span></p>

                <p><strong>Fecha:</strong> <span id="detalleVentaFecha"></span></p>

                <p><strong>Cliente:</strong> <span id="detalleVentaCliente"></span></p>

                <p><strong>Método de pago:</strong> <span id="detalleVentaPago"></span></p>

                <p><strong>Comprobante:</strong> <span id="detalleVentaComprobante"></span></p>

                <p><strong>Número:</strong> <span id="detalleVentaNumero"></span></p>

                <p><strong>Usuario:</strong> <span id="detalleVentaUsuario"></span></p>

                <p><strong>Total:</strong> Bs <span id="detalleVentaTotal"></span></p>

            </div>



            <div class="overflow-x-auto">

                <table class="w-full border-collapse">

                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-3">Producto</th>
                            <th class="border p-3">Presentación</th>
                            <th class="border p-3">Cantidad</th>
                            <th class="border p-3">Unidades reales</th>
                            <th class="border p-3">Precio</th>
                            <th class="border p-3">Subtotal</th>
                        </tr>
                    </thead>



                    <tbody id="detalleVentaProductos"></tbody>

                </table>

            </div>

        </div>

    </div>





    <script>
        let productosVenta = [];
        let indice = 0;
        let productoTemporalVenta = null;

        function abrirDetalleVenta(venta)
        {
            document.getElementById('detalleVentaId').textContent =
                venta.numero_comprobante ?? '-';

            document.getElementById('detalleVentaFecha').textContent =
                venta.fecha ?? '-';

            document.getElementById('detalleVentaCliente').textContent =
                venta.cliente?.nombre ?? 'Consumidor final';

            document.getElementById('detalleVentaPago').textContent =
                venta.metodo_pago ?? '-';

            document.getElementById('detalleVentaComprobante').textContent =
                venta.tipo_comprobante ?? '-';

            document.getElementById('detalleVentaNumero').textContent =
                venta.numero_comprobante ?? '-';

            document.getElementById('detalleVentaUsuario').textContent =
                venta.usuario?.name ?? '-';

            document.getElementById('detalleVentaTotal').textContent =
                Number(venta.total ?? 0).toFixed(2);

            const tbody =
                document.getElementById('detalleVentaProductos');

            tbody.innerHTML = '';

            if (!venta.detalles || venta.detalles.length === 0) {

                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="border p-4 text-center text-gray-500">
                            No hay detalle disponible.
                        </td>
                    </tr>
                `;

            } else {

                venta.detalles.forEach(detalle => {

                    const nombre =
                        detalle.producto?.nombre ?? 'Producto';

                    const presentacion =
                        detalle.presentacion?.nombre ?? 'Unidad';

                    const cantidadPresentaciones =
                        Number(
                            detalle.cantidad_presentaciones ??
                            detalle.cantidad ??
                            0
                        );

                    const cantidadUnidades =
                        Number(detalle.cantidad ?? 0);

                    const precio =
                        Number(detalle.precio_venta ?? 0);

                    const subtotal =
                        Number(detalle.subtotal ?? 0);

                    tbody.innerHTML += `
                        <tr>
                            <td class="border p-3">${nombre}</td>
                            <td class="border p-3 text-center">${presentacion}</td>
                            <td class="border p-3 text-center">${cantidadPresentaciones}</td>
                            <td class="border p-3 text-center">${cantidadUnidades}</td>
                            <td class="border p-3 text-right">Bs ${precio.toFixed(2)}</td>
                            <td class="border p-3 text-right">Bs ${subtotal.toFixed(2)}</td>
                        </tr>
                    `;
                });
            }

            document.getElementById('modalDetalleVenta').style.display =
                'flex';
        }


        function cerrarDetalleVenta()
        {
            document.getElementById('modalDetalleVenta').style.display =
                'none';
        }


        function seleccionarProductoVenta(tarjeta)
        {
            const stock =
                parseInt(tarjeta.dataset.stock || 0, 10);

            if (stock <= 0) {
                alert('Este producto está agotado.');
                return;
            }

            let presentaciones = [];

            try {
                presentaciones =
                    JSON.parse(tarjeta.dataset.presentaciones || '[]');
            } catch (e) {
                presentaciones = [];
            }

            productoTemporalVenta = {
                producto_id: tarjeta.dataset.id,
                nombre: tarjeta.dataset.nombre,
                precio_base: parseFloat(tarjeta.dataset.precio || 0),
                stock: stock,
                presentaciones: presentaciones
            };

            document.getElementById('productoSeleccionadoNombre').textContent =
                productoTemporalVenta.nombre;

            document.getElementById('ventaStockDisponible').textContent =
                productoTemporalVenta.stock;

            document.getElementById('ventaCantidadPresentacion').value = 1;

            const select =
                document.getElementById('ventaPresentacionSelect');

            select.innerHTML =
                '<option value="">Unidad</option>';

            presentaciones
                .filter(p => p.estado)
                .forEach(p => {

                    const option =
                        document.createElement('option');

                    option.value =
                        p.id;

                    option.textContent =
                        `${p.nombre} (${p.cantidad_unidades} unidades)`;

                    option.dataset.nombre =
                        p.nombre;

                    option.dataset.unidades =
                        p.cantidad_unidades;

                    option.dataset.precioVenta =
                        p.precio_venta ?? '';

                    select.appendChild(option);
                });

            document.getElementById('ventaPrecioPresentacion').value =
                productoTemporalVenta.precio_base.toFixed(2);

            document.getElementById('modalSeleccionPresentacion').style.display =
                'flex';
        }


        function actualizarPrecioPresentacionVenta()
        {
            if (!productoTemporalVenta) {
                return;
            }

            const select =
                document.getElementById('ventaPresentacionSelect');

            const option =
                select.options[select.selectedIndex];

            let precio =
                productoTemporalVenta.precio_base;

            if (
                option &&
                option.value &&
                option.dataset.precioVenta !== ''
            ) {
                precio =
                    parseFloat(option.dataset.precioVenta || 0);
            }

            document.getElementById('ventaPrecioPresentacion').value =
                precio.toFixed(2);
        }


        function cerrarSeleccionPresentacion()
        {
            document.getElementById('modalSeleccionPresentacion').style.display =
                'none';

            productoTemporalVenta =
                null;
        }


        function confirmarProductoVenta()
        {
            if (!productoTemporalVenta) {
                return;
            }

            const select =
                document.getElementById('ventaPresentacionSelect');

            const option =
                select.options[select.selectedIndex];

            const presentacionId =
                select.value || '';

            const presentacionNombre =
                presentacionId
                    ? (
                        option.dataset.nombre ||
                        option.textContent
                    )
                    : 'Unidad';

            const unidadesPorPresentacion =
                presentacionId
                    ? parseInt(
                        option.dataset.unidades || 1,
                        10
                    )
                    : 1;

            const cantidadPresentaciones =
                parseInt(
                    document.getElementById(
                        'ventaCantidadPresentacion'
                    ).value || 0,
                    10
                );

            const precio =
                parseFloat(
                    document.getElementById(
                        'ventaPrecioPresentacion'
                    ).value || 0
                );

            if (
                !cantidadPresentaciones ||
                cantidadPresentaciones < 1
            ) {
                alert('La cantidad debe ser mayor a 0.');
                return;
            }

            const unidadesReales =
                cantidadPresentaciones *
                unidadesPorPresentacion;

            if (
                unidadesReales >
                productoTemporalVenta.stock
            ) {
                alert(
                    'Stock insuficiente. Disponible: '
                    + productoTemporalVenta.stock
                    + ' unidades.'
                );
                return;
            }

            const existente =
                productosVenta.find(
                    item =>
                        String(item.producto_id) ===
                            String(productoTemporalVenta.producto_id)
                        &&
                        String(item.presentacion_id || '') ===
                            String(presentacionId || '')
                );

            if (existente) {

                const nuevaCantidadPresentaciones =
                    existente.cantidad_presentaciones +
                    cantidadPresentaciones;

                const nuevasUnidades =
                    nuevaCantidadPresentaciones *
                    existente.unidades_por_presentacion;

                if (nuevasUnidades > existente.stock) {

                    alert(
                        'Stock insuficiente. Disponible: '
                        + existente.stock
                        + ' unidades.'
                    );
                    return;
                }

                existente.cantidad_presentaciones =
                    nuevaCantidadPresentaciones;

                existente.cantidad =
                    nuevasUnidades;

                existente.subtotal =
                    existente.cantidad_presentaciones *
                    existente.precio;

            } else {

                productosVenta.push({
                    indice: indice,
                    producto_id: productoTemporalVenta.producto_id,
                    nombre: productoTemporalVenta.nombre,
                    presentacion_id: presentacionId,
                    presentacion_nombre: presentacionNombre,
                    unidades_por_presentacion: unidadesPorPresentacion,
                    cantidad_presentaciones: cantidadPresentaciones,
                    cantidad: unidadesReales,
                    precio: precio,
                    stock: productoTemporalVenta.stock,
                    subtotal:
                        cantidadPresentaciones *
                        precio
                });

                indice++;
            }

            cerrarSeleccionPresentacion();
            renderizarVenta();
        }


        function filtrarTarjetasProductos()
        {
            const texto =
                document
                    .getElementById('buscarProductoVenta')
                    .value
                    .toLowerCase()
                    .trim();

            document
                .querySelectorAll('.tarjeta-producto')
                .forEach(card => {

                    const codigo =
                        card.dataset.codigo || '';

                    const nombre =
                        card.dataset.busqueda || '';

                    const coincide =
                        codigo.includes(texto) ||
                        nombre.includes(texto);

                    card.style.display =
                        coincide ? 'block' : 'none';
                });
        }


        function disminuirProducto(indiceItem)
        {
            const item =
                productosVenta.find(
                    producto =>
                        producto.indice === indiceItem
                );

            if (!item) {
                return;
            }

            if (item.cantidad_presentaciones > 1) {

                item.cantidad_presentaciones -= 1;

                item.cantidad =
                    item.cantidad_presentaciones *
                    item.unidades_por_presentacion;

                item.subtotal =
                    item.cantidad_presentaciones *
                    item.precio;

            } else {

                productosVenta =
                    productosVenta.filter(
                        producto =>
                            producto.indice !== indiceItem
                    );
            }

            renderizarVenta();
        }


        function eliminarProducto(indiceItem)
        {
            productosVenta =
                productosVenta.filter(
                    item =>
                        item.indice !== indiceItem
                );

            renderizarVenta();
        }


        function renderizarVenta()
        {
            const tbody =
                document.getElementById('detalleVenta');

            tbody.innerHTML = '';

            let total = 0;

            if (productosVenta.length === 0) {

                tbody.innerHTML = `
                    <tr>
                        <td
                            colspan="7"
                            class="border p-4 text-center text-gray-500"
                        >
                            No hay productos agregados.
                        </td>
                    </tr>
                `;

            } else {

                productosVenta.forEach(
                    (item, posicion) => {

                        total += item.subtotal;

                        const fila =
                            document.createElement('tr');

                        fila.innerHTML = `
                            <td class="border p-3">
                                ${item.nombre}

                                <input
                                    type="hidden"
                                    name="productos[${posicion}][producto_id]"
                                    value="${item.producto_id}"
                                >
                            </td>

                            <td class="border p-3 text-center">
                                ${item.presentacion_nombre}

                                <input
                                    type="hidden"
                                    name="productos[${posicion}][presentacion_id]"
                                    value="${item.presentacion_id}"
                                >
                            </td>

                            <td class="border p-3 text-center">
                                ${item.cantidad_presentaciones}

                                <input
                                    type="hidden"
                                    name="productos[${posicion}][cantidad_presentaciones]"
                                    value="${item.cantidad_presentaciones}"
                                >
                            </td>

                            <td class="border p-3 text-center">
                                ${item.cantidad}
                            </td>

                            <td class="border p-3 text-right">
                                Bs ${item.precio.toFixed(2)}
                            </td>

                            <td class="border p-3 text-right">
                                Bs ${item.subtotal.toFixed(2)}
                            </td>

                            <td class="border p-3 text-center">

                                <div style="display:flex;justify-content:center;gap:6px;align-items:center;">

                                    <button
                                        type="button"
                                        onclick="disminuirProducto(${item.indice})"
                                        style="
                                            background-color:#6b7280;
                                            color:white;
                                            width:30px;
                                            height:30px;
                                            border-radius:6px;
                                            border:none;
                                            cursor:pointer;
                                            font-weight:bold;
                                        "
                                    >
                                        −
                                    </button>

                                    <button
                                        type="button"
                                        onclick="eliminarProducto(${item.indice})"
                                        style="
                                            background-color:#dc2626;
                                            color:white;
                                            padding:6px 10px;
                                            border-radius:6px;
                                            border:none;
                                            cursor:pointer;
                                        "
                                    >
                                        Quitar
                                    </button>

                                </div>

                            </td>
                        `;

                        tbody.appendChild(fila);
                    }
                );
            }

            document
                .getElementById('totalVenta')
                .textContent =
                total.toFixed(2);
        }


        document
            .getElementById('formVenta')
            .addEventListener(
                'submit',
                function(event)
                {
                    if (productosVenta.length === 0) {

                        event.preventDefault();

                        alert(
                            'Debe agregar al menos un producto.'
                        );
                    }
                }
            );
    </script>



</x-app-layout>