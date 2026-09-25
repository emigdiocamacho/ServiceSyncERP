<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Tenants\Models\Tenant;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    /** @use HasFactory<\Database\Factories\App\Domains\Inventory\Models\WarehouseFactory> */
    use HasFactory, HasUids;

    protected $table = 'warehouses';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'location',
        'manager_id',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    // ==========================================
    // RELACIONES DE ELOQUENT
    // ==========================================

    /**
     * Empresa (Tenant) a la que pertenece este almacén.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Encargado/Responsable del almacén.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    
}
