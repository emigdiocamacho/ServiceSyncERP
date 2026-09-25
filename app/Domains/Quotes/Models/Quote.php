<?php

namespace App\Domains\Quotes\Models;

use App\Domains\Customers\Models\Customer;
use App\Domains\Tenants\Models\Tenant;
use App\Domains\Users\Models\User;
use App\Domains\WorkOrders\Models\WorkOrder;
use Illuminate\Database\Eloquent\Concerns\HasUids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    /** @use HasFactory<\Database\Factories\App\Domains\Quotes\Models\QuoteFactory> */
    use HasFactory, HasUids;

    protected $table = 'quotes';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'user_id',
        'work_order_id',
        'number',
        'status',
        'issued_at',
        'expires_at',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'notes',
        'terms_and_conditions',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expires_at' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // ==========================================
    // RELACIONES DE ELOQUENT
    // ==========================================

    /**
     * Empresa (Tenant) a la que pertenece esta cotización.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Cliente al que va dirigida la cotización.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Vendedor / Asesor comercial que creó la propuesta.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Orden de Trabajo resultante tras la aprobación de la cotización.
     */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }
}
