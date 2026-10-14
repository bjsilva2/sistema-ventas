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















                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">















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































                    </div>















                    <hr class="my-6">















                    <h4 class="font-bold mb-4">







                        Productos de la compra







                    </h4>















                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">















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

                                        data-presentaciones='@json($producto->presentaciones)'

                                    >







                                        {{ $producto->codigo }} - {{ $producto->nombre }}







                                    </option>







                                @endforeach







                            </select>







                        </div>



                        <div>

                            <label class="block mb-1 font-semibold">

                                Presentación

                            </label>



                            <select

                                id="presentacionSelect"

                                class="w-full border-gray-300 rounded-lg"

                            >

                                <option value="">

                                    Unidad

                                </option>

                            </select>

                        </div>

















                        <div>







                            <label class="block mb-1 font-semibold">







                                Cantidad paquetes/unidades







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



                            <label class="block mb-1 font-semibold">



                                Fecha de vencimiento



                            </label>







                            <input



                                type="date"



                                id="fechaVencimientoInput"



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
                                    <th class="border p-3">Presentación</th>
                                    <th class="border p-3">Cantidad paquetes/unidades</th>
                                    <th class="border p-3">Unidades reales</th>
                                    <th class="border p-3">Precio</th>
                                    <th class="border p-3">Subtotal</th>
                                    <th class="border p-3">Vencimiento</th>
                                    <th class="border p-3">Acción</th>
                                </tr>
                            </thead>















                            <tbody id="detalleCompra">















                                <tr id="filaVacia">







                                    <td colspan="8" class="border p-4 text-center text-gray-500">







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
                            <th class="border p-3">Presentación</th>
                            <th class="border p-3">Cantidad paquetes/unidades</th>
                            <th class="border p-3">Unidades reales</th>
                            <th class="border p-3">Precio</th>
                            <th class="border p-3">Subtotal</th>
                            <th class="border p-3">Vencimiento</th>
                        </tr>
                    </thead>







                    <tbody id="detalleCompraProductos"></tbody>







                </table>







            </div>







        </div>







    </div>























    <script>

        document.addEventListener('DOMContentLoaded', function () {



            let productosCompra = [];

            let indice = 0;



            const productoSelect = document.getElementById('productoSelect');

            const presentacionSelect = document.getElementById('presentacionSelect');

            const cantidadInput = document.getElementById('cantidadInput');

            const precioInput = document.getElementById('precioInput');

            const fechaVencimientoInput = document.getElementById('fechaVencimientoInput');

            const detalleCompra = document.getElementById('detalleCompra');

            const totalCompra = document.getElementById('totalCompra');

            const formCompra = document.getElementById('formCompra');



            function cargarPresentaciones() {

                const option = productoSelect.options[productoSelect.selectedIndex];



                presentacionSelect.innerHTML =

                    '<option value="">Unidad</option>';



                if (!option || !option.value) {

                    precioInput.value = '';

                    return;

                }



                const precioBase =

                    Number(option.dataset.precio || 0);



                precioInput.value =

                    precioBase.toFixed(2);



                let presentaciones = [];



                try {

                    presentaciones =

                        JSON.parse(option.dataset.presentaciones || '[]');

                } catch (e) {

                    presentaciones = [];

                }



                presentaciones

                    .filter(p => p.estado)

                    .forEach(p => {

                        const opt = document.createElement('option');



                        opt.value = p.id;

                        opt.textContent =

                            `${p.nombre} (${p.cantidad_unidades} unidades)`;



                        opt.dataset.unidades =

                            p.cantidad_unidades;



                        opt.dataset.precioCompra =

                            p.precio_compra ?? '';



                        opt.dataset.nombre =

                            p.nombre;



                        presentacionSelect.appendChild(opt);

                    });

            }



            productoSelect.addEventListener(

                'change',

                cargarPresentaciones

            );



            presentacionSelect.addEventListener(

                'change',

                function () {



                    const productoOption =

                        productoSelect.options[

                            productoSelect.selectedIndex

                        ];



                    const presentacionOption =

                        presentacionSelect.options[

                            presentacionSelect.selectedIndex

                        ];



                    if (

                        presentacionOption &&

                        presentacionOption.value &&

                        presentacionOption.dataset.precioCompra !== ''

                    ) {

                        precioInput.value =

                            Number(

                                presentacionOption.dataset.precioCompra

                            ).toFixed(2);

                    } else {

                        precioInput.value =

                            Number(

                                productoOption?.dataset.precio || 0

                            ).toFixed(2);

                    }

                }

            );





            window.agregarProducto = function () {



                const productoOption =

                    productoSelect.options[

                        productoSelect.selectedIndex

                    ];



                const presentacionOption =

                    presentacionSelect.options[

                        presentacionSelect.selectedIndex

                    ];



                const productoId =

                    productoSelect.value;



                if (!productoId || !productoOption) {

                    alert('Seleccione un producto.');

                    return;

                }



                const nombre =

                    productoOption.dataset.nombre || 'Producto';



                const presentacionId =

                    presentacionSelect.value || '';



                const presentacionNombre =

                    presentacionId

                        ? (

                            presentacionOption.dataset.nombre ||

                            presentacionOption.textContent

                        )

                        : 'Unidad';



                const unidadesPorPresentacion =

                    presentacionId

                        ? parseInt(

                            presentacionOption.dataset.unidades || 1,

                            10

                        )

                        : 1;



                const cantidadPresentaciones =

                    parseInt(cantidadInput.value, 10);



                const precio =

                    parseFloat(precioInput.value);



                const fechaVencimiento =

                    fechaVencimientoInput.value || '';



                if (

                    !cantidadPresentaciones ||

                    cantidadPresentaciones < 1

                ) {

                    alert('La cantidad debe ser mayor a 0.');

                    return;

                }



                if (Number.isNaN(precio) || precio < 0) {

                    alert('Ingrese un precio válido.');

                    return;

                }



                const unidadesReales =

                    cantidadPresentaciones *

                    unidadesPorPresentacion;



                const subtotal =

                    cantidadPresentaciones *

                    precio;



                productosCompra.push({

                    indice,

                    producto_id: productoId,

                    nombre,

                    presentacion_id: presentacionId,

                    presentacion_nombre: presentacionNombre,

                    unidades_por_presentacion: unidadesPorPresentacion,

                    cantidad_presentaciones: cantidadPresentaciones,

                    cantidad: unidadesReales,

                    precio,

                    subtotal,

                    fecha_vencimiento: fechaVencimiento

                });



                indice++;



                renderizarDetalle();



                productoSelect.value = '';

                presentacionSelect.innerHTML =

                    '<option value="">Unidad</option>';

                cantidadInput.value = 1;

                precioInput.value = '';

                fechaVencimientoInput.value = '';

            };





            window.eliminarProducto = function (indiceItem) {



                productosCompra =

                    productosCompra.filter(

                        item =>

                            item.indice !== indiceItem

                    );



                renderizarDetalle();

            };





            function renderizarDetalle() {



                detalleCompra.innerHTML = '';



                let total = 0;



                if (productosCompra.length === 0) {



                    detalleCompra.innerHTML = `

                        <tr>

                            <td

                                colspan="8"

                                class="border p-4 text-center text-gray-500"

                            >

                                No hay productos agregados.

                            </td>

                        </tr>

                    `;



                    totalCompra.textContent = '0.00';

                    return;

                }



                productosCompra.forEach(

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

                                ${item.fecha_vencimiento || '-'}



                                <input

                                    type="hidden"

                                    name="productos[${posicion}][fecha_vencimiento]"

                                    value="${item.fecha_vencimiento}"

                                >

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



                        detalleCompra.appendChild(fila);

                    }

                );



                totalCompra.textContent =

                    total.toFixed(2);

            }





            formCompra.addEventListener(

                'submit',

                function (event) {



                    if (productosCompra.length === 0) {



                        event.preventDefault();



                        alert(

                            'Debe agregar al menos un producto a la compra.'

                        );

                    }

                }

            );

        });





        window.abrirDetalleCompra = function (compra) {



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

                        <td colspan="7" class="border p-4 text-center text-gray-500">

                            No hay detalle disponible.

                        </td>

                    </tr>

                `;



            } else {



                compra.detalles.forEach(detalle => {



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

                        Number(detalle.precio_compra ?? 0);



                    const subtotal =

                        Number(detalle.subtotal ?? 0);



                    const vencimiento =

                        detalle.fecha_vencimiento ?? '-';



                    tbody.innerHTML += `

                        <tr>

                            <td class="border p-3">${nombre}</td>

                            <td class="border p-3 text-center">${presentacion}</td>

                            <td class="border p-3 text-center">${cantidadPresentaciones}</td>

                            <td class="border p-3 text-center">${cantidadUnidades}</td>

                            <td class="border p-3 text-right">Bs ${precio.toFixed(2)}</td>

                            <td class="border p-3 text-right">Bs ${subtotal.toFixed(2)}</td>

                            <td class="border p-3 text-center">${vencimiento}</td>

                        </tr>

                    `;

                });

            }



            document.getElementById('modalDetalleCompra').style.display =

                'flex';

        };





        window.cerrarDetalleCompra = function () {



            document.getElementById('modalDetalleCompra').style.display =

                'none';

        };

    </script>















</x-app-layout>