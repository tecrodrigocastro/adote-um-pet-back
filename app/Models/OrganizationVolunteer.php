<?php

namespace App\Models;

use App\Enums\VolunteerRole;
use Illuminate\Database\Eloquent\Model;

class OrganizationVolunteer extends Model
{
    protected $fillable = [
        'organization_id',
        'volunteer_id',
        'role',
        'permissions',
        'active',
        'joined_at',
    ];

    protected $casts = [
        'permissions' => 'array',
        'joined_at' => 'datetime',
        'role' => VolunteerRole::class,
    ];

    public function organization()
    {
        return $this->belongsTo(User::class, 'organization_id');
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = $this->permissions ?? [];

        // Admin tem todas as permissões
        if ($this->role === VolunteerRole::ADMIN) {
            return true;
        }

        // Verificar permissões específicas do role
        $rolePermissions = $this->role->permissions();
        if (in_array($permission, $rolePermissions)) {
            return true;
        }

        // Verificar permissões customizadas
        return in_array($permission, $permissions);
    }

    public function canManageVolunteers(): bool
    {
        return $this->hasPermission('manage_volunteers');
    }

    public function canApproveAdoptions(): bool
    {
        return $this->hasPermission('approve_adoptions');
    }

    public function canCreatePets(): bool
    {
        return $this->hasPermission('create_pets');
    }

    public function canEditPets(): bool
    {
        return $this->hasPermission('edit_pets');
    }

    public function canDeletePets(): bool
    {
        return $this->hasPermission('delete_pets');
    }

    public function canViewReports(): bool
    {
        return $this->hasPermission('view_reports');
    }

    public function canConfigureOrganization(): bool
    {
        return $this->hasPermission('configure_organization');
    }
}
