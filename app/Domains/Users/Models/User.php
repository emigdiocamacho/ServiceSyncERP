<?php

namespace App\Domains\Users\Models;

use App\Domains\Tenants\Models\Tenant;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ==========================================
    // 1. RELACIÓN MUCHOS A MUCHOS CON TENANTS
    // ==========================================

    /**
     * Obtiene todos los Tenants (empresas) a las que pertenece el usuario.
     */
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Requisito de la interfaz HasTenants:
     * Devuelve las empresas a las que el usuario tiene acceso en el panel /app.
     */
    public function getTenants(Panel $panel): Collection
    {
        return $this->tenants;
    }

    /**
     * Requisito de la interfaz HasTenants:
     * Verifica si el usuario tiene acceso a un Tenant específico.
     */
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->tenants->contains($tenant);
    }

    // ==========================================
    // 2. CONTROL DE ACCESO A PANELES (FilamentUser)
    // ==========================================

    /**
     * Determina a qué paneles (/admin o /app) puede ingresar el usuario.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Si intenta entrar al panel /admin (Panel Central de Proveedor SaaS)
        if ($panel->getId() === 'admin') {
            return $this->hasRole('super_admin');
        }

        // Si intenta entrar al panel /app (Panel Multi-Tenant)
        if ($panel->getId() === 'app') {
            return true; // Cualquier usuario registrado puede acceder a /app
        }

        return false;
    }
}