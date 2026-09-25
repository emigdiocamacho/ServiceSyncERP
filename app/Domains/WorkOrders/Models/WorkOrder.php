<?php

namespace App\Domains\WorkOrders\Models;

use App\Domains\Customers\Models\Customer;
use App\Domains\Tenants\Models\Tenant;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrder extends Model
{
    protected $fillable = [
        'tenant_id',
        'customer_id',
        'assigned_user_id',
        'number',
        'title',
        'description',
        'status',
        'priority',
        'total_amount',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function tenant():BelongTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Técnico/Empleado asignado a ejecutar la orden.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

}
