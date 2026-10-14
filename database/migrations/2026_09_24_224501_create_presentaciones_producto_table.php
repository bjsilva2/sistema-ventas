<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presentaciones_producto', function (Blueprint $table) {
            $table->id();

            $table->foreignId('producto_id')
                ->constrained('productos')
                ->cascadeOnDelete();

            $table->string('nombre');

            $table->integer('cantidad_unidades')
                ->default(1);

            $table->decimal('precio_compra', 10, 2)
                ->nullable();

            $table->decimal('precio_venta', 10, 2)
                ->nullable();

            $table->boolean('estado')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presentaciones_producto');
    }
};