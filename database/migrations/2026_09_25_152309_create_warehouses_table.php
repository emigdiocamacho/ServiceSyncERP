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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Aislamiento Multi-tenant (UUID)
            $table->foreignUuid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();

            // Identificación y Datos del Almacén
            $table->string('code'); // Ej: ALM-01, CENTRAL, DEPOSITO-NORTE
            $table->string('name'); // Ej: Almacén Principal, Depósito de Materiales
            $table->string('location')->nullable(); // Dirección física o referencia
            
            // Responsable del almacén (opcional, usuario de la tabla users)
            $table->foreignId('manager_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Estado y banderas de configuración
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false); // Para marcar el almacén principal por defecto

            $table->timestamps();

            // Restricción de Unicidad: El código de almacén es único POR EMPRESA
            $table->unique(['tenant_id', 'code']);

            // Índice para acelerar filtrados en Filament y reportes de inventario
            $table->index(['tenant_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
