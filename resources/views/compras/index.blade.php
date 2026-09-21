<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Compras
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
                    Nueva Compra
                </h3>

                <form action="{{ route('compras.store') }}" method="POST" id="formCompra">

                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div>
                            <label class="block mb-1 font-semibold">
                                Proveedor
                            </label>

                            <select
                                name="proveedor_id"
                                class="w-full border-gray-300 rounded-lg"
                                required
                            >
                                <option value="">
                                    Seleccione proveedor
                                </option>

                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">
                                        {{ $proveedor->nombre }}
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
                                Número de comprobante
                            </label>

                            <input
                                type="text"
                                name="numero_comprobante"
                                class="w-full border-gray-300 rounded-lg"
                                placeholder="Ejemplo: FAC-001"
                            >
                        </div>

                    </div>

                    <hr class="my-6">

                    <h4 class="font-bold mb-4">
                        Productos de la compra
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
                                        data-precio="{{ $producto->precio_compra }}"
                                    >
                                        {{ $producto->codigo }} - {{ $producto->nombre }}
                                    </option>
                                @endforeach
                            </select>
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
                            <label class="block mb-1 font-semibold">
                                Precio compra
                            </label>

                            <input
                                type="number"
                                id="precioInput"
                                min="0"
                                step="0.01"
                                class="w-full border-gray-300 rounded-lg"
                            >
                        </div>

                        <div>
                            <button
                                type="button"
                                onclick="agregarProducto()"
                                style="
                                    background-color: #16a34a;
                                    color: white;
                                    padding: 10px 18px;
                                    border-radius: 8px;
                                    border: none;
                                    font-weight: 600;
                                    cursor: pointer;
                                    width: 100%;
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

                            <tbody id="detalleCompra">

                                <tr id="filaVacia">
                                    <td colspan="5" class="border p-4 text-center text-gray-500">
                                        No hay productos agregados.
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <div class="mt-5 text-right">

                        <div class="text-xl font-bold">
                            Total: Bs <span id="totalCompra">0.00</span>
                        </div>

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
                            Registrar Compra
                        </button>

                    </div>

                </form>

            </div>


            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4">
                    Compras registradas
                </h3>

                <form method="GET" action="{{ route('compras.index') }}" class="mb-5">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                        <div>
                            <label class="block mb-1 font-semibold">Buscar</label>
                            <input
                                type="text"
                                name="buscar"
                                value="{{ request('buscar') }}"
                                placeholder="Proveedor o comprobante..."
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

                            @if(request('buscar') || request('fecha_inicio') || request('fecha_fin'))
                                <a
                                    href="{{ route('compras.index') }}"
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
                                <th class="border p-3">Proveedor</th>
                                <th class="border p-3">Comprobante</th>
                                <th class="border p-3">Usuario</th>
                                <th class="border p-3">Total</th>
                                <th class="border p-3">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($compras as $compra)

                                <tr>
                                    <td class="border p-3 text-center">
                                        {{ $compra->id }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $compra->fecha }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $compra->proveedor->nombre ?? '-' }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $compra->numero_comprobante ?? '-' }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $compra->usuario->name ?? '-' }}
                                    </td>

                                    <td class="border p-3 text-right">
                                        Bs {{ number_format($compra->total, 2) }}
                                    </td>

                                    <td class="border p-3 text-center">
                                        <button
                                            type="button"
                                            onclick='abrirDetalleCompra(@json($compra))'
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
                                        colspan="7"
                                        class="border p-5 text-center text-gray-500"
                                    >
                                        No hay compras registradas.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-6">
                    {{ $compras->links() }}
                </div>

            </div>

        </div>
    </div>


    {{-- MODAL DETALLE DE COMPRA --}}
    <div
        id="modalDetalleCompra"
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
                max-width:850px;
                max-height:90vh;
                overflow-y:auto;
                padding:24px;
                border-radius:12px;
            "
        >
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold">
                    Detalle de Compra
                </h3>

                <button
                    type="button"
                    onclick="cerrarDetalleCompra()"
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
                <p><strong>Compra N.º:</strong> <span id="detalleCompraId"></span></p>
                <p><strong>Fecha:</strong> <span id="detalleCompraFecha"></span></p>
                <p><strong>Proveedor:</strong> <span id="detalleCompraProveedor"></span></p>
                <p><strong>Comprobante:</strong> <span id="detalleCompraComprobante"></span></p>
                <p><strong>Usuario:</strong> <span id="detalleCompraUsuario"></span></p>
                <p><strong>Total:</strong> Bs <span id="detalleCompraTotal"></span></p>
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
                    <tbody id="detalleCompraProductos"></tbody>
                </table>
            </div>
        </div>
    </div>


    <script>

        function abrirDetalleCompra(compra)
        {
            document.getElementById('detalleCompraId').textContent =
                compra.id ?? '-';

            document.getElementById('detalleCompraFecha').textContent =
                compra.fecha ?? '-';

            document.getElementById('detalleCompraProveedor').textContent =
                compra.proveedor?.nombre ?? '-';

            document.getElementById('detalleCompraComprobante').textContent =
                compra.numero_comprobante ?? '-';

            document.getElementById('detalleCompraUsuario').textContent =
                compra.usuario?.name ?? '-';

            document.getElementById('detalleCompraTotal').textContent =
                Number(compra.total ?? 0).toFixed(2);

            const tbody =
                document.getElementById('detalleCompraProductos');

            tbody.innerHTML = '';

            if (!compra.detalles || compra.detalles.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="border p-4 text-center text-gray-500">
                            No hay detalle disponible.
                        </td>
                    </tr>
                `;
            } else {
                compra.detalles.forEach(detalle => {
                    const nombre =
                        detalle.producto?.nombre ?? 'Producto';

                    const cantidad =
                        Number(detalle.cantidad ?? 0);

                    const precio =
                        Number(detalle.precio_compra ?? 0);

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

            document.getElementById('modalDetalleCompra').style.display =
                'flex';
        }

        function cerrarDetalleCompra()
        {
            document.getElementById('modalDetalleCompra').style.display =
                'none';
        }


        let productosCompra = [];
        let indice = 0;

        const productoSelect = document.getElementById('productoSelect');
        const cantidadInput = document.getElementById('cantidadInput');
        const precioInput = document.getElementById('precioInput');

        productoSelect.addEventListener('change', function () {

            const option =
                productoSelect.options[productoSelect.selectedIndex];

            if (option.value) {
                precioInput.value =
                    parseFloat(option.dataset.precio || 0).toFixed(2);
            } else {
                precioInput.value = '';
            }

        });


        function agregarProducto()
        {
            const option =
                productoSelect.options[productoSelect.selectedIndex];

            const productoId = productoSelect.value;
            const nombre = option.dataset.nombre;

            const cantidad =
                parseInt(cantidadInput.value);

            const precio =
                parseFloat(precioInput.value);

            if (!productoId) {
                alert('Seleccione un producto.');
                return;
            }

            if (!cantidad || cantidad < 1) {
                alert('La cantidad debe ser mayor a 0.');
                return;
            }

            if (isNaN(precio) || precio < 0) {
                alert('Ingrese un precio válido.');
                return;
            }

            const subtotal =
                cantidad * precio;

            const item = {
                indice: indice,
                producto_id: productoId,
                nombre: nombre,
                cantidad: cantidad,
                precio: precio,
                subtotal: subtotal
            };

            productosCompra.push(item);

            indice++;

            renderizarDetalle();

            productoSelect.value = '';
            cantidadInput.value = 1;
            precioInput.value = '';
        }


        function eliminarProducto(indiceItem)
        {
            productosCompra =
                productosCompra.filter(
                    item => item.indice !== indiceItem
                );

            renderizarDetalle();
        }


        function renderizarDetalle()
        {
            const tbody =
                document.getElementById('detalleCompra');

            tbody.innerHTML = '';

            let total = 0;

            if (productosCompra.length === 0) {

                tbody.innerHTML = `
                    <tr>
                        <td colspan="5"
                            class="border p-4 text-center text-gray-500">
                            No hay productos agregados.
                        </td>
                    </tr>
                `;

            } else {

                productosCompra.forEach((item, posicion) => {

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

                            ${item.cantidad}

                            <input
                                type="hidden"
                                name="productos[${posicion}][cantidad]"
                                value="${item.cantidad}"
                            >

                        </td>

                        <td class="border p-3 text-right">

                            Bs ${item.precio.toFixed(2)}

                            <input
                                type="hidden"
                                name="productos[${posicion}][precio_compra]"
                                value="${item.precio}"
                            >

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

                    tbody.appendChild(fila);

                });

            }

            document.getElementById('totalCompra').textContent =
                total.toFixed(2);
        }


        document
            .getElementById('formCompra')
            .addEventListener('submit', function(event) {

                if (productosCompra.length === 0) {

                    event.preventDefault();

                    alert(
                        'Debe agregar al menos un producto a la compra.'
                    );

                }

            });

    </script>

</x-app-layout>