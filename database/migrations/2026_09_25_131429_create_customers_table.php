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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // Relación con la empresa/tenant (Aislamiento Multi-tenant)
            // Si usas UUIDs para tenants, mantén foreignUuid. De lo contrario, usa foreignId.
            $table->foreignUuid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();

            // Tipo de cliente (Persona natural o Empresa)
            $table->enum('type', ['individual', 'company'])->default('company');

            // Datos principales
            $table->string('name');
            $table->string('tax_id')->nullable()->index(); // RIF / NIT / CUIT / DNI
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            
            // Ubicación y geolocalización para mapas en Filament
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
