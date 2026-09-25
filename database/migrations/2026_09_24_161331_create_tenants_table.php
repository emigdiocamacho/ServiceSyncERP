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
        Schema::create('tenants', function (Blueprint $table) {

            $table->uuid('id')->primary(); // Clave primaria UUID
            
            $table->string('name');
            $table->string('slug')->unique(); // Ej: 'akuaro-sedan' para URLs dinámicas
            $table->string('tax_id')->nullable(); // RIF / NIT / CUIT / CIF
            
            $table->string('logo_url')->nullable();
            $table->string('primary_color')->default('#0f172a'); // Color de marca para la UI de Filament
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();

        });

        Schema::create('tenant_user', function (Blueprint $table) {
            // Relación con el Tenant (UUID)
            $table->foreignUuid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();

            // Relación con el Usuario (BigInteger / ID Convencional)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Rol específico del usuario dentro de ESTA empresa concreta
            $table->string('role')->default('member'); // Ej: 'owner', 'manager', 'technician', 'accountant'

            // Timestamps para auditoría (cuándo se unió el usuario a la empresa)
            $table->timestamps();

            // Clave primaria compuesta para evitar duplicados del mismo usuario en la misma empresa
            $table->primary(['tenant_id', 'user_id']);
            
            // Índice para acelerar búsquedas de todas las empresas de un usuario
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('tenant_user');
    }
};
