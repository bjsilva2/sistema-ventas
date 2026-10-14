<x-app-layout>







    <x-slot name="header">



        <h2 class="font-semibold text-xl text-gray-800 leading-tight">



            Gestión de Productos



        </h2>



    </x-slot>







    <div class="py-6">







        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">







            {{-- MENSAJE DE ÉXITO --}}



            @if(session('success'))



                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">



                    {{ session('success') }}



                </div>



            @endif







            {{-- ERRORES --}}



            @if ($errors->any())



                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">



                    <ul class="list-disc pl-5">



                        @foreach ($errors->all() as $error)



                            <li>{{ $error }}</li>



                        @endforeach



                    </ul>



                </div>



            @endif











            {{-- NUEVO PRODUCTO --}}



            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">







                <h3 class="text-lg font-bold mb-5">



                    Nuevo Producto



                </h3>







                <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">







                    @csrf







                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">







                        {{-- CÓDIGO --}}



                        <div>



                            <label class="block mb-1 font-semibold">



                                Código



                            </label>







                            <input



                                type="text"



                                name="codigo"



                                value="{{ old('codigo') }}"



                                class="w-full border-gray-300 rounded-lg"



                                placeholder="Ejemplo: PROD001"



                                required



                            >



                        </div>











                        {{-- NOMBRE --}}



                        <div>



                            <label class="block mb-1 font-semibold">



                                Nombre



                            </label>







                            <input



                                type="text"



                                name="nombre"



                                value="{{ old('nombre') }}"



                                class="w-full border-gray-300 rounded-lg"



                                placeholder="Ejemplo: Coca Cola 2L"



                                required



                            >



                        </div>











                        {{-- CATEGORÍA --}}



                        <div>



                            <label class="block mb-1 font-semibold">



                                Categoría



                            </label>







                            <select



                                name="categoria_id"



                                class="w-full border-gray-300 rounded-lg"



                                required



                            >







                                <option value="">



                                    Seleccione una categoría



                                </option>







                                @foreach($categorias as $categoria)







                                    <option



                                        value="{{ $categoria->id }}"



                                        {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}



                                    >



                                        {{ $categoria->nombre }}



                                    </option>







                                @endforeach







                            </select>



                        </div>











                        {{-- PRECIO COMPRA --}}



                        <div>



                            <label class="block mb-1 font-semibold">



                                Precio compra



                            </label>







                            <input



                                type="number"



                                step="0.01"



                                min="0"



                                name="precio_compra"



                                value="{{ old('precio_compra') }}"



                                class="w-full border-gray-300 rounded-lg"



                                placeholder="0.00"



                                required



                            >



                        </div>











                        {{-- PRECIO VENTA --}}



                        <div>



                            <label class="block mb-1 font-semibold">



                                Precio venta



                            </label>







                            <input



                                type="number"



                                step="0.01"



                                min="0"



                                name="precio_venta"



                                value="{{ old('precio_venta') }}"



                                class="w-full border-gray-300 rounded-lg"



                                placeholder="0.00"



                                required



                            >



                        </div>











                        {{-- STOCK --}}



                        <div>



                            <label class="block mb-1 font-semibold">



                                Stock



                            </label>







                            <input



                                type="number"



                                min="0"



                                name="stock"



                                value="{{ old('stock', 0) }}"



                                class="w-full border-gray-300 rounded-lg"



                                required



                            >



                        </div>











                        {{-- STOCK MÍNIMO --}}



                        <div>



                            <label class="block mb-1 font-semibold">



                                Stock mínimo



                            </label>







                            <input



                                type="number"



                                min="0"



                                name="stock_minimo"



                                value="{{ old('stock_minimo', 0) }}"



                                class="w-full border-gray-300 rounded-lg"



                                required



                            >



                        </div>







                    </div>











                    {{-- DESCRIPCIÓN --}}



                    <div class="mt-4">







                        <label class="block mb-1 font-semibold">



                            Descripción



                        </label>







                        <textarea



                            name="descripcion"



                            rows="3"



                            class="w-full border-gray-300 rounded-lg"



                            placeholder="Descripción del producto"



                        >{{ old('descripcion') }}</textarea>







                    </div>







                    {{-- IMAGEN --}}



                    <div class="mt-4">



                        <label class="block mb-1 font-semibold">



                            Imagen del producto



                        </label>







                        <input



                            type="file"



                            name="imagen"



                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"



                            class="w-full border-gray-300 rounded-lg"



                        >







                        <p class="text-sm text-gray-500 mt-1">



                            JPG, JPEG, PNG o WEBP. Máximo 2 MB.



                        </p>



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



                            Guardar Producto



                        </button>







                    </div>







                </form>







            </div>











            {{-- TABLA --}}



            <div class="bg-white shadow-sm rounded-lg p-6">







                <h3 class="text-lg font-bold mb-4">



                    Productos registrados



                </h3>



                <form method="GET" action="{{ route('productos.index') }}" class="mb-4">



                    <div class="flex gap-2">



                        <input



                            type="text"



                            name="buscar"



                            value="{{ request('buscar') }}"



                            placeholder="Buscar por código, nombre o descripción..."



                            class="w-full border-gray-300 rounded-lg"



                        >







                        <button



                            type="submit"



                            style="



                                background:#2563eb;



                                color:white;



                                padding:10px 20px;



                                border-radius:8px;



                                border:none;



                                font-weight:600;



                            "



                        >



                            Buscar



                        </button>







                        @if(request('buscar'))



                            <a



                                href="{{ route('productos.index') }}"



                                style="



                                    background:#6b7280;



                                    color:white;



                                    padding:10px 20px;



                                    border-radius:8px;



                                    text-decoration:none;



                                "



                            >



                                Limpiar



                            </a>



                                @endif







                    </div>







                </form>







                <div class="overflow-x-auto">







                    <table class="w-full border-collapse text-sm">







                        <thead>







                            <tr class="bg-gray-100">







                                <th class="border p-3">ID</th>







                                <th class="border p-3">Imagen</th>







                                <th class="border p-3">Código</th>







                                <th class="border p-3">Nombre</th>







                                <th class="border p-3">Categoría</th>







                                <th class="border p-3">Compra</th>







                                <th class="border p-3">Venta</th>







                                <th class="border p-3">Stock</th>







                                <th class="border p-3">Mínimo</th>







                                <th class="border p-3">Estado</th>







                                <th class="border p-3">Acciones</th>







                            </tr>







                        </thead>











                        <tbody>







                            @forelse($productos as $producto)







                                <tr>







                                    <td class="border p-3 text-center">



                                        {{ $producto->id }}



                                    </td>







                                    <td class="border p-3 text-center">



                                        @if($producto->imagen)



                                            <img



                                                src="{{ asset('storage/' . $producto->imagen) }}"



                                                alt="{{ $producto->nombre }}"



                                                style="



                                                    width:64px;



                                                    height:64px;



                                                    object-fit:cover;



                                                    border-radius:10px;



                                                    margin:auto;



                                                    border:1px solid #e5e7eb;



                                                "



                                            >



                                        @else



                                            <div



                                                style="



                                                    width:64px;



                                                    height:64px;



                                                    border-radius:10px;



                                                    margin:auto;



                                                    background:#f3f4f6;



                                                    display:flex;



                                                    align-items:center;



                                                    justify-content:center;



                                                    color:#9ca3af;



                                                    font-size:12px;



                                                    border:1px solid #e5e7eb;



                                                "



                                            >



                                                Sin imagen



                                            </div>



                                        @endif



                                    </td>







                                    <td class="border p-3">



                                        {{ $producto->codigo }}



                                    </td>







                                    <td class="border p-3">



                                        {{ $producto->nombre }}



                                    </td>







                                    <td class="border p-3">



                                        {{ $producto->categoria->nombre ?? 'Sin categoría' }}



                                    </td>







                                    <td class="border p-3 text-center">



                                        Bs {{ number_format($producto->precio_compra, 2) }}



                                    </td>







                                    <td class="border p-3 text-center">



                                        Bs {{ number_format($producto->precio_venta, 2) }}



                                    </td>







                                    <td class="border p-3 text-center">







                                        @if($producto->stock <= $producto->stock_minimo)







                                            <span class="text-red-600 font-bold">



                                                {{ $producto->stock }}



                                            </span>







                                        @else







                                            <span>



                                                {{ $producto->stock }}



                                            </span>







                                        @endif







                                    </td>







                                    <td class="border p-3 text-center">



                                        {{ $producto->stock_minimo }}



                                    </td>







                                    <td class="border p-3 text-center">







                                        @if($producto->estado)







                                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold">



                                                Activo



                                            </span>







                                        @else







                                            <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full font-semibold">



                                                Inactivo



                                            </span>







                                        @endif







                                    </td>











                                    <td class="border p-3">







                                        <div class="flex justify-center gap-2">







                                            {{-- EDITAR --}}



                                            <button



                                                type="button"



                                                onclick='abrirEditarProducto(@json($producto))'



                                                style="



                                                    background-color: #f59e0b;



                                                    color: white;



                                                    padding: 8px 14px;



                                                    border-radius: 8px;



                                                    font-weight: 600;



                                                    border: none;



                                                    cursor: pointer;



                                                "



                                            >



                                                Editar



                                            </button>











                                            {{-- PRESENTACIONES --}}

                                            <button

                                                type="button"

                                                onclick='abrirPresentaciones(@json($producto))'

                                                style="

                                                    background-color:#0f766e;

                                                    color:white;

                                                    padding:8px 14px;

                                                    border-radius:8px;

                                                    font-weight:600;

                                                    border:none;

                                                    cursor:pointer;

                                                "

                                            >

                                                Presentaciones

                                            </button>





                                            {{-- ACTIVAR / DESACTIVAR --}}



                                            <form



                                                action="{{ route('productos.destroy', $producto) }}"



                                                method="POST"



                                            >







                                                @csrf



                                                @method('DELETE')







                                                @if($producto->estado)







                                                    <button



                                                        type="submit"



                                                        style="



                                                            background-color: #dc2626;



                                                            color: white;



                                                            padding: 8px 14px;



                                                            border-radius: 8px;



                                                            font-weight: 600;



                                                            border: none;



                                                            cursor: pointer;



                                                        "



                                                    >



                                                        Desactivar



                                                    </button>







                                                @else







                                                    <button



                                                        type="submit"



                                                        style="



                                                            background-color: #16a34a;



                                                            color: white;



                                                            padding: 8px 14px;



                                                            border-radius: 8px;



                                                            font-weight: 600;



                                                            border: none;



                                                            cursor: pointer;



                                                        "



                                                    >



                                                        Activar



                                                    </button>







                                                @endif







                                            </form>







                                        </div>







                                    </td>







                                </tr>







                            @empty







                                <tr>







                                    <td



                                        colspan="11"



                                        class="border p-6 text-center text-gray-500"



                                    >



                                        No hay productos registrados.



                                    </td>







                                </tr>







                            @endforelse







                        </tbody>







                    </table>







                </div>







                <div class="mt-6">



                    {{ $productos->links() }}



                </div>







            </div>







        </div>







    </div>











    {{-- MODAL PRESENTACIONES --}}

    <div

        id="modalPresentaciones"

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

                <div>

                    <h3 class="text-xl font-bold">

                        Presentaciones del producto

                    </h3>

                    <p class="text-sm text-gray-500 mt-1">

                        <span id="presentacionProductoNombre"></span>

                    </p>

                </div>



                <button

                    type="button"

                    onclick="cerrarPresentaciones()"

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



            <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                <strong>Configura cada producto libremente:</strong>
                escribe cuántas unidades contiene esta presentación.
                Por ejemplo, una caja puede tener 6 unidades en un producto y 24 en otro.
            </div>

            <form action="{{ route('presentaciones.store') }}" method="POST" class="mb-6">

                @csrf



                <input

                    type="hidden"

                    name="producto_id"

                    id="presentacionProductoId"

                >



                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">



                    <div>

                        <label class="block mb-1 font-semibold">Nombre de presentación</label>

                        <input

                            type="text"

                            name="nombre"

                            placeholder="Ejemplo: Caja, Pack, Bandeja..."

                            class="w-full border-gray-300 rounded-lg"

                            required

                        >

                    </div>



                    <div>

                        <label class="block mb-1 font-semibold">Unidades que contiene</label>

                        <input

                            type="number"

                            name="cantidad_unidades"

                            min="1"

                            value="1"

                            class="w-full border-gray-300 rounded-lg"

                            required

                        >

                    </div>



                    <div>

                        <label class="block mb-1 font-semibold">Precio compra del paquete</label>

                        <input

                            type="number"

                            name="precio_compra"

                            min="0"

                            step="0.01"

                            class="w-full border-gray-300 rounded-lg"

                            placeholder="0.00"

                        >

                    </div>



                    <div>

                        <label class="block mb-1 font-semibold">Precio venta del paquete</label>

                        <input

                            type="number"

                            name="precio_venta"

                            min="0"

                            step="0.01"

                            class="w-full border-gray-300 rounded-lg"

                            placeholder="0.00"

                        >

                    </div>



                </div>



                <div class="mt-4">

                    <button

                        type="submit"

                        style="

                            background:#2563eb;

                            color:white;

                            padding:10px 18px;

                            border:none;

                            border-radius:8px;

                            font-weight:600;

                            cursor:pointer;

                        "

                    >

                        Guardar presentación

                    </button>

                </div>

            </form>



            <div class="overflow-x-auto">

                <table class="w-full border-collapse">

                    <thead>

                        <tr class="bg-gray-100">

                            <th class="border p-3">Nombre</th>

                            <th class="border p-3">Unidades por presentación</th>

                            <th class="border p-3">Compra</th>

                            <th class="border p-3">Venta</th>

                            <th class="border p-3">Estado</th>

                            <th class="border p-3">Acción</th>

                        </tr>

                    </thead>

                    <tbody id="tablaPresentacionesProducto">

                        <tr>

                            <td colspan="6" class="border p-4 text-center text-gray-500">

                                No hay presentaciones registradas.

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>



        </div>

    </div>





    {{-- MODAL EDITAR PRODUCTO --}}



    <div



        id="modalEditarProducto"



        style="



            display: none;



            position: fixed;



            inset: 0;



            background-color: rgba(0,0,0,0.5);



            align-items: center;



            justify-content: center;



            z-index: 9999;



            overflow-y: auto;



            padding: 20px;



        "



    >







        <div



            style="



                background: white;



                width: 100%;



                max-width: 700px;



                padding: 24px;



                border-radius: 12px;



            "



        >







            <h3 class="text-xl font-bold mb-5">



                Editar Producto



            </h3>











            <form id="formEditarProducto" method="POST" enctype="multipart/form-data">







                @csrf



                @method('PUT')











                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">







                    <div>



                        <label class="block mb-1 font-semibold">



                            Código



                        </label>







                        <input



                            id="editarCodigo"



                            name="codigo"



                            type="text"



                            class="w-full border-gray-300 rounded-lg"



                            required



                        >



                    </div>











                    <div>



                        <label class="block mb-1 font-semibold">



                            Nombre



                        </label>







                        <input



                            id="editarNombre"



                            name="nombre"



                            type="text"



                            class="w-full border-gray-300 rounded-lg"



                            required



                        >



                    </div>











                    <div>



                        <label class="block mb-1 font-semibold">



                            Categoría



                        </label>







                        <select



                            id="editarCategoria"



                            name="categoria_id"



                            class="w-full border-gray-300 rounded-lg"



                            required



                        >







                            @foreach($categorias as $categoria)







                                <option value="{{ $categoria->id }}">



                                    {{ $categoria->nombre }}



                                </option>







                            @endforeach







                        </select>



                    </div>











                    <div>



                        <label class="block mb-1 font-semibold">



                            Precio compra



                        </label>







                        <input



                            id="editarPrecioCompra"



                            name="precio_compra"



                            type="number"



                            step="0.01"



                            min="0"



                            class="w-full border-gray-300 rounded-lg"



                            required



                        >



                    </div>











                    <div>



                        <label class="block mb-1 font-semibold">



                            Precio venta



                        </label>







                        <input



                            id="editarPrecioVenta"



                            name="precio_venta"



                            type="number"



                            step="0.01"



                            min="0"



                            class="w-full border-gray-300 rounded-lg"



                            required



                        >



                    </div>











                    <div>



                        <label class="block mb-1 font-semibold">



                            Stock



                        </label>







                        <input



                            id="editarStock"



                            name="stock"



                            type="number"



                            min="0"



                            class="w-full border-gray-300 rounded-lg"



                            required



                        >



                    </div>











                    <div>



                        <label class="block mb-1 font-semibold">



                            Stock mínimo



                        </label>







                        <input



                            id="editarStockMinimo"



                            name="stock_minimo"



                            type="number"



                            min="0"



                            class="w-full border-gray-300 rounded-lg"



                            required



                        >



                    </div>







                </div>











                <div class="mt-4">







                    <label class="block mb-1 font-semibold">



                        Descripción



                    </label>







                    <textarea



                        id="editarDescripcion"



                        name="descripcion"



                        rows="3"



                        class="w-full border-gray-300 rounded-lg"



                    ></textarea>







                </div>







                {{-- IMAGEN ACTUAL / CAMBIAR IMAGEN --}}



                <div class="mt-4">



                    <label class="block mb-1 font-semibold">



                        Imagen actual



                    </label>







                    <div class="mb-3">



                        <img



                            id="imagenActualProducto"



                            src=""



                            alt="Imagen actual"



                            style="



                                display:none;



                                width:110px;



                                height:110px;



                                object-fit:cover;



                                border-radius:10px;



                                border:1px solid #e5e7eb;



                            "



                        >







                        <div



                            id="sinImagenActual"



                            style="



                                width:110px;



                                height:110px;



                                border-radius:10px;



                                background:#f3f4f6;



                                display:flex;



                                align-items:center;



                                justify-content:center;



                                color:#9ca3af;



                                font-size:13px;



                                border:1px solid #e5e7eb;



                            "



                        >



                            Sin imagen



                        </div>



                    </div>







                    <label class="block mb-1 font-semibold">



                        Cambiar imagen



                    </label>







                    <input



                        type="file"



                        name="imagen"



                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"



                        class="w-full border-gray-300 rounded-lg"



                    >







                    <p class="text-sm text-gray-500 mt-1">



                        Déjalo vacío para conservar la imagen actual.



                    </p>



                </div>











                <div class="flex justify-end gap-2 mt-5">







                    <button



                        type="button"



                        onclick="cerrarEditarProducto()"



                        style="



                            background-color: #6b7280;



                            color: white;



                            padding: 9px 18px;



                            border-radius: 8px;



                            border: none;



                            cursor: pointer;



                        "



                    >



                        Cancelar



                    </button>











                    <button



                        type="submit"



                        style="



                            background-color: #2563eb;



                            color: white;



                            padding: 9px 18px;



                            border-radius: 8px;



                            border: none;



                            cursor: pointer;



                        "



                    >



                        Guardar cambios



                    </button>







                </div>







            </form>







        </div>







    </div>











    <script>







        function abrirEditarProducto(producto)



        {



            document.getElementById('editarCodigo').value =



                producto.codigo ?? '';







            document.getElementById('editarNombre').value =



                producto.nombre ?? '';







            document.getElementById('editarCategoria').value =



                producto.categoria_id ?? '';







            document.getElementById('editarPrecioCompra').value =



                producto.precio_compra ?? 0;







            document.getElementById('editarPrecioVenta').value =



                producto.precio_venta ?? 0;







            document.getElementById('editarStock').value =



                producto.stock ?? 0;







            document.getElementById('editarStockMinimo').value =



                producto.stock_minimo ?? 0;







            document.getElementById('editarDescripcion').value =



                producto.descripcion ?? '';







            const imagenActual =



                document.getElementById('imagenActualProducto');







            const sinImagen =



                document.getElementById('sinImagenActual');







            if (producto.imagen) {



                imagenActual.src = '/storage/' + producto.imagen;



                imagenActual.style.display = 'block';



                sinImagen.style.display = 'none';



            } else {



                imagenActual.src = '';



                imagenActual.style.display = 'none';



                sinImagen.style.display = 'flex';



            }







            document.getElementById('formEditarProducto').action =



                '/productos/' + producto.id;







            document.getElementById('modalEditarProducto').style.display =



                'flex';



        }











        function abrirPresentaciones(producto)

        {

            document.getElementById('presentacionProductoId').value =

                producto.id;



            document.getElementById('presentacionProductoNombre').textContent =

                producto.nombre ?? '';



            const tbody =

                document.getElementById('tablaPresentacionesProducto');



            tbody.innerHTML = '';



            const presentaciones =

                producto.presentaciones ?? [];



            if (presentaciones.length === 0) {



                tbody.innerHTML = `

                    <tr>

                        <td colspan="6" class="border p-4 text-center text-gray-500">

                            No hay presentaciones registradas.

                        </td>

                    </tr>

                `;



            } else {



                presentaciones.forEach(presentacion => {



                    const estadoTexto =

                        presentacion.estado ? 'Activo' : 'Inactivo';



                    const colorEstado =

                        presentacion.estado ? '#16a34a' : '#dc2626';



                    const botonTexto =

                        presentacion.estado ? 'Desactivar' : 'Activar';



                    const botonColor =

                        presentacion.estado ? '#dc2626' : '#16a34a';



                    tbody.innerHTML += `

                        <tr>

                            <td class="border p-3">${presentacion.nombre}</td>

                            <td class="border p-3 text-center">${presentacion.cantidad_unidades}</td>

                            <td class="border p-3 text-right">

                                Bs ${Number(presentacion.precio_compra ?? 0).toFixed(2)}

                            </td>

                            <td class="border p-3 text-right">

                                Bs ${Number(presentacion.precio_venta ?? 0).toFixed(2)}

                            </td>

                            <td class="border p-3 text-center">

                                <span style="color:${colorEstado};font-weight:600;">

                                    ${estadoTexto}

                                </span>

                            </td>

                            <td class="border p-3 text-center">

                                <form

                                    action="/presentaciones/${presentacion.id}/estado"

                                    method="POST"

                                >

                                    <input type="hidden" name="\_token" value="{{ csrf_token() }}">

                                    <input type="hidden" name="\_method" value="PUT">



                                    <button

                                        type="submit"

                                        style="

                                            background:${botonColor};

                                            color:white;

                                            padding:7px 12px;

                                            border:none;

                                            border-radius:7px;

                                            cursor:pointer;

                                            font-weight:600;

                                        "

                                    >

                                        ${botonTexto}

                                    </button>

                                </form>

                            </td>

                        </tr>

                    `;

                });

            }



            document.getElementById('modalPresentaciones').style.display =

                'flex';

        }





        function cerrarPresentaciones()

        {

            document.getElementById('modalPresentaciones').style.display =

                'none';

        }





        function cerrarEditarProducto()



        {



            document.getElementById('modalEditarProducto').style.display =



                'none';



        }







    </script>







</x-app-layout>