<?php

namespace App\Domains\Tenants\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Concerns\HasUids;

class Tenant extends Model implements HasName
{
    /** @use HasFactory<\Database\Factories\App\Domains\Tenants\Models\TenantFactory> */
    use HasFactory, HasUids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
        'tax_id',
        'logo_url',
        'primary_color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getFilamentName(): string
    {
        return $this->name;
    }

    /**
     * Usuarios pertenecientes a esta empresa (Relación Muchos a Muchos con pivote de rol).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Clientes registrados por esta empresa.
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Almacenes de esta empresa.
     */
    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    /**
     * Productos y servicios de esta empresa.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Cotizaciones/Presupuestos creados por esta empresa.
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * Órdenes de trabajo ejecutadas por esta empresa.
     */
    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }
    
}
