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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // 1. Aislamiento por Empresa / Tenant (UUID)
            $table->foreignUuid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();

            // 2. Cliente asociado a la orden (UUID)
            $table->foreignUuid('customer_id')
                  ->constrained('customers')
                  ->cascadeOnDelete();

            // 3. Usuario/Técnico asignado (ID entero autoincremental de la tabla users)
            $table->foreignId('assigned_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // 4. Identificación y Contenido de la Orden
            $table->string('number'); // Ej: OT-2026-0001
            $table->string('title');
            $table->text('description')->nullable();

            // 5. Estados y Prioridades
            $table->string('status')->default('pending');  // pending, in_progress, completed, cancelled
            $table->string('priority')->default('medium'); // low, medium, high, urgent

            // 6. Fechas y Montos
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            // 7. Restricción de Unicidad: El número de orden es único POR TENANT
            $table->unique(['tenant_id', 'number']);

            // 8. Índices para acelerar búsquedas en tableros y reportes
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
