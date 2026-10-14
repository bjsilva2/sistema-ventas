<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('detalle_compras', 'fecha_vencimiento')) {
            Schema::table('detalle_compras', function (Blueprint $table) {
                $table->date('fecha_vencimiento')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('detalle_compras', 'fecha_vencimiento')) {
            Schema::table('detalle_compras', function (Blueprint $table) {
                $table->dropColumn('fecha_vencimiento');
            });
        }
    }
};