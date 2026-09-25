<?php

namespace App\Domains\Customers\Models;

use App\Domains\Quotes\Models\Quote;
use App\Domains\Tenants\Models\Tenant;
use App\Domains\WorkOrders\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\App\Domains\Customers\Models\CustomerFactory> */
    use HasFactory;

    /**
     * Campos que se pueden llenar masivamente a través de formularios de Filament.
     */
    protected $fillable = [
        'tenant_id',
        'type',
        'name',
        'tax_id',
        'email',
        'phone',
        'address',
        'latitude',
        'longitude',
    ];

    /**
     * Conversiones automáticas de tipos de datos.
     */
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones de Eloquent
    |--------------------------------------------------------------------------
    */

    /**
     * El cliente pertenece a una empresa/tenant específica.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Un cliente puede tener múltiples contactos asociados.
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(CustomerContact::class);
    }

    /**
     * Un cliente puede tener múltiples cotizaciones o presupuestos.
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * Un cliente puede tener múltiples órdenes de trabajo/servicios.
     */
    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

}
