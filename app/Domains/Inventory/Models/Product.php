<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Tenants\Models\Tenant;
use App\Domains\WorkOrders\Models\WorkOrderItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\App\Domains\Inventory\Models\ProductFactory> */
    use HasFactory;

    /**
     * Atributos asignables en masa desde los formularios de Filament.
     */
    protected $fillable = [
        'tenant_id',
        'sku',
        'name',
        'type', // 'product' o 'service'
        'unit_price',
        'cost_price',
        'min_stock_alert',
    ];

    /**
     * Conversiones automáticas de tipos de datos.
     */
    protected $casts = [
        'unit_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'min_stock_alert' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Métodos de Ayuda (Helpers / Lógica de Negocio)
    |--------------------------------------------------------------------------
    */

    /**
     * Determina si el ítem es un producto físico que requiere manejo de stock.
     */
    public function isPhysicalProduct(): bool
    {
        return $this->type === 'product';
    }

    /**
     * Calcula el margen de ganancia unitario.
     */
    public function getProfitMarginAttribute(): float
    {
        return (float) ($this->unit_price - $this->cost_price);
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones de Eloquent
    |--------------------------------------------------------------------------
    */

    /**
     * Pertenece a una empresa/tenant.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relación con Almacenes a través de la tabla pivote de Inventario (Stock por Almacén).
     */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'inventory_stocks')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * Registros de uso en ítems de órdenes de trabajo.
     */
    public function workOrderItems(): HasMany
    {
        return $this->hasMany(WorkOrderItem::class);
    }

}
