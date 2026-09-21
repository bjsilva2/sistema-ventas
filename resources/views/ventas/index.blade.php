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
                                <option value="">
                                    Seleccione
                                </option>

                                <option value="Efectivo">
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

                    <div class="mt-4">

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

                    <h4 class="font-bold mb-4">
                        Productos de la venta
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                        <div>
                            <label class="block mb-1 font-semibold">
                                Producto
                            </label>

                            <select
                                id="productoSelect"
                                class="w-full border-gray-300 rounded-lg"
                            >
                                <option value="">
                                    Seleccione producto
                                </option>

                                @foreach($productos as $producto)

                                    <option
                                        value="{{ $producto->id }}"
                                        data-nombre="{{ $producto->nombre }}"
                                        data-precio="{{ $producto->precio_venta }}"
                                        data-stock="{{ $producto->stock }}"
                                    >
                                        {{ $producto->codigo }}
                                        -
                                        {{ $producto->nombre }}
                                        (Stock: {{ $producto->stock }})
                                    </option>

                                @endforeach

                            </select>
                        </div>

                        <div>
                            <label class="block mb-1 font-semibold">
                                Precio
                            </label>

                            <input
                                type="number"
                                id="precioInput"
                                class="w-full border-gray-300 rounded-lg bg-gray-100"
                                readonly
                            >
                        </div>

                        <div>
                            <label class="block mb-1 font-semibold">
                                Cantidad
                            </label>

                            <input
                                type="number"
                                id="cantidadInput"
                                min="1"
                                value="1"
                                class="w-full border-gray-300 rounded-lg"
                            >
                        </div>

                        <div>
                            <button
                                type="button"
                                onclick="agregarProducto()"
                                style="
                                    background-color:#16a34a;
                                    color:white;
                                    padding:10px 18px;
                                    border-radius:8px;
                                    border:none;
                                    font-weight:600;
                                    cursor:pointer;
                                    width:100%;
                                "
                            >
                                Agregar producto
                            </button>
                        </div>

                    </div>

                    <div class="mt-6 overflow-x-auto">

                        <table class="w-full border-collapse">

                            <thead>

                                <tr class="bg-gray-100">
                                    <th class="border p-3">Producto</th>
                                    <th class="border p-3">Cantidad</th>
                                    <th class="border p-3">Precio</th>
                                    <th class="border p-3">Subtotal</th>
                                    <th class="border p-3">Acción</th>
                                </tr>

                            </thead>

                            <tbody id="detalleVenta">

                                <tr>
                                    <td
                                        colspan="5"
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
                                        {{ $venta->tipo_comprobante ?? '-' }}
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
                <p><strong>Venta N.º:</strong> <span id="detalleVentaId"></span></p>
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
                            <th class="border p-3">Cantidad</th>
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

        function abrirDetalleVenta(venta)
        {
            document.getElementById('detalleVentaId').textContent =
                venta.id ?? '-';

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
                        <td colspan="4" class="border p-4 text-center text-gray-500">
                            No hay detalle disponible.
                        </td>
                    </tr>
                `;
            } else {
                venta.detalles.forEach(detalle => {
                    const nombre =
                        detalle.producto?.nombre ?? 'Producto';

                    const cantidad =
                        Number(detalle.cantidad ?? 0);

                    const precio =
                        Number(detalle.precio_venta ?? 0);

                    const subtotal =
                        Number(detalle.subtotal ?? (cantidad * precio));

                    tbody.innerHTML += `
                        <tr>
                            <td class="border p-3">${nombre}</td>
                            <td class="border p-3 text-center">${cantidad}</td>
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


        let productosVenta = [];
        let indice = 0;

        const productoSelect =
            document.getElementById('productoSelect');

        const precioInput =
            document.getElementById('precioInput');

        const cantidadInput =
            document.getElementById('cantidadInput');


        productoSelect.addEventListener(
            'change',
            function()
            {
                const option =
                    productoSelect.options[
                        productoSelect.selectedIndex
                    ];

                if(option.value)
                {
                    precioInput.value =
                        parseFloat(
                            option.dataset.precio || 0
                        ).toFixed(2);
                }
                else
                {
                    precioInput.value = '';
                }
            }
        );


        function agregarProducto()
        {
            const option =
                productoSelect.options[
                    productoSelect.selectedIndex
                ];

            const productoId =
                productoSelect.value;

            if(!productoId)
            {
                alert('Seleccione un producto.');
                return;
            }

            const nombre =
                option.dataset.nombre;

            const precio =
                parseFloat(
                    option.dataset.precio
                );

            const stock =
                parseInt(
                    option.dataset.stock
                );

            const cantidad =
                parseInt(
                    cantidadInput.value
                );

            if(!cantidad || cantidad < 1)
            {
                alert(
                    'Ingrese una cantidad válida.'
                );

                return;
            }

            if(cantidad > stock)
            {
                alert(
                    'Stock insuficiente. Disponible: '
                    + stock
                );

                return;
            }

            const existente =
                productosVenta.find(
                    item =>
                        item.producto_id == productoId
                );

            if(existente)
            {
                alert(
                    'Este producto ya fue agregado.'
                );

                return;
            }

            const subtotal =
                cantidad * precio;

            productosVenta.push({
                indice: indice,
                producto_id: productoId,
                nombre: nombre,
                precio: precio,
                cantidad: cantidad,
                stock: stock,
                subtotal: subtotal
            });

            indice++;

            renderizarVenta();

            productoSelect.value = '';
            precioInput.value = '';
            cantidadInput.value = 1;
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
                document.getElementById(
                    'detalleVenta'
                );

            tbody.innerHTML = '';

            let total = 0;

            if(productosVenta.length === 0)
            {
                tbody.innerHTML = `
                    <tr>
                        <td
                            colspan="5"
                            class="border p-4 text-center text-gray-500"
                        >
                            No hay productos agregados.
                        </td>
                    </tr>
                `;
            }
            else
            {
                productosVenta.forEach(
                    (item, posicion) =>
                    {
                        total += item.subtotal;

                        const fila =
                            document.createElement(
                                'tr'
                            );

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

                                ${item.cantidad}

                                <input
                                    type="hidden"
                                    name="productos[${posicion}][cantidad]"
                                    value="${item.cantidad}"
                                >

                            </td>

                            <td class="border p-3 text-right">
                                Bs ${item.precio.toFixed(2)}
                            </td>

                            <td class="border p-3 text-right">
                                Bs ${item.subtotal.toFixed(2)}
                            </td>

                            <td class="border p-3 text-center">

                                <button
                                    type="button"
                                    onclick="eliminarProducto(${item.indice})"
                                    style="
                                        background-color:#dc2626;
                                        color:white;
                                        padding:6px 12px;
                                        border-radius:6px;
                                        border:none;
                                        cursor:pointer;
                                    "
                                >
                                    Quitar
                                </button>

                            </td>
                        `;

                        tbody.appendChild(
                            fila
                        );
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
                    if(
                        productosVenta.length === 0
                    )
                    {
                        event.preventDefault();

                        alert(
                            'Debe agregar al menos un producto.'
                        );
                    }
                }
            );

    </script>

</x-app-layout>