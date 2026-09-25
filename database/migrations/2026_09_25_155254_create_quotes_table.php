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
        Schema::create('quotes', function (Blueprint $table) {
            // Clave primaria UUID
            $table->uuid('id')->primary();

            // 1. Aislamiento Multi-tenant (UUID)
            $table->foreignUuid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();

            // 2. Cliente asignado (UUID)
            $table->foreignUuid('customer_id')
                  ->constrained('customers')
                  ->cascadeOnDelete();

            // 3. Vendedor / Asesor comercial que emite la cotización
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // 4. Si la cotización es aprobada, se vincula opcionalmente a la Orden de Trabajo generada
            $table->foreignUuid('work_order_id')
                  ->nullable()
                  ->constrained('work_orders')
                  ->nullOnDelete();

            // 5. Identificación y Estado
            $table->string('number'); // Ej: COT-2026-0001
            $table->string('status')->default('draft'); // draft, sent, approved, rejected, expired
            
            // 6. Fechas clave
            $table->date('issued_at'); // Fecha de emisión
            $table->date('expires_at')->nullable(); // Fecha de vencimiento / validez de la oferta

            // 7. Cálculos financieros
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00); // Impuestos/IVA
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);

            // 8. Notas y condiciones comerciales
            $table->text('notes')->nullable();
            $table->text('terms_and_conditions')->nullable();

            $table->timestamps();

            // Restricción de Unicidad: Número de cotización único por Tenant
            $table->unique(['tenant_id', 'number']);

            // Índices para búsquedas rápidas en Filament
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
