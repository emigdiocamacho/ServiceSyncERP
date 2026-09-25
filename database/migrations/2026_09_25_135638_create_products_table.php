<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Relación con el Tenant
            $table->foreignUuid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();

            $table->string('sku')->index(); // Código único o referencia
            $table->string('name');
            
            // Tipo: 'product' (producto físico) o 'service' (servicio/mano de obra)
            $table->enum('type', ['product', 'service'])->default('product');

            // Precios y costos
            $table->decimal('unit_price', 12, 2)->default(0.00);
            $table->decimal('cost_price', 12, 2)->default(0.00);
            
            // Alerta de stock mínimo
            $table->integer('min_stock_alert')->default(5);

            $table->timestamps();

            // Evitar SKUs duplicados dentro de un mismo Tenant
            $table->unique(['tenant_id', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
