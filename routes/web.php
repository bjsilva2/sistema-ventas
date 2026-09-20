<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CajaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // PERFIL
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // CATEGORÍAS
    Route::get('/categorias', [CategoriaController::class, 'index'])
        ->name('categorias.index');

    Route::post('/categorias', [CategoriaController::class, 'store'])
        ->name('categorias.store');

    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])
        ->name('categorias.update');

    Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])
        ->name('categorias.destroy');


    // PRODUCTOS
    Route::get('/productos', [ProductoController::class, 'index'])
        ->name('productos.index');

    Route::post('/productos', [ProductoController::class, 'store'])
        ->name('productos.store');

    Route::put('/productos/{producto}', [ProductoController::class, 'update'])
        ->name('productos.update');

    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])
        ->name('productos.destroy');
        
        // CLIENTES
    Route::get('/clientes', [ClienteController::class, 'index'])
        ->name('clientes.index');
        
    Route::post('/clientes', [ClienteController::class, 'store'])
        ->name('clientes.store');
        
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])
        ->name('clientes.update');

    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])
        ->name('clientes.destroy');
        // PROVEEDORES
    Route::get('/proveedores', [ProveedorController::class, 'index'])
        ->name('proveedores.index');

    Route::post('/proveedores', [ProveedorController::class, 'store'])
        ->name('proveedores.store');
    
    Route::put('/proveedores/{proveedor}', [ProveedorController::class, 'update'])
        ->name('proveedores.update');
        
    Route::delete('/proveedores/{proveedor}', [ProveedorController::class, 'destroy'])
        ->name('proveedores.destroy');

        // COMPRAS
    Route::get('/compras', [CompraController::class, 'index'])
        ->name('compras.index');

    Route::post('/compras', [CompraController::class, 'store'])
        ->name('compras.store');

        // VENTAS
    Route::get('/ventas', [VentaController::class, 'index'])
        ->name('ventas.index');
        
    Route::post('/ventas', [VentaController::class, 'store'])
        ->name('ventas.store');

        // INVENTARIO
    Route::get('/inventario', [InventarioController::class, 'index'])
        ->name('inventario.index');

        // CAJA
    Route::get('/caja', [CajaController::class, 'index'])
        ->name('caja.index');

    Route::post('/caja/abrir', [CajaController::class, 'abrir'])
        ->name('caja.abrir');

    Route::post('/caja/movimiento', [CajaController::class, 'movimiento'])
        ->name('caja.movimiento');

    Route::post('/caja/cerrar', [CajaController::class, 'cerrar'])
        ->name('caja.cerrar');

});

require __DIR__.'/auth.php';