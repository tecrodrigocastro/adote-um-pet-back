<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'photo_url',
        'user_type',
        'cnpj',
        'organization_name',
        'responsible_name',
        'mission_statement',
        'website',
        'social_media',
        'verified',
        'verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'user_type' => UserType::class,
            'social_media' => 'array',
            'verified_at' => 'datetime',
        ];
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'user_id');
    }

    public function adoptions()
    {
        return $this->hasMany(Adoption::class, 'adopter_id');
    }

    public function receivedAdoptionRequests()
    {
        return $this->hasManyThrough(Adoption::class, Pet::class, 'user_id', 'pet_id');
    }

    public function ownedPets()
    {
        return $this->hasMany(Pet::class, 'user_id');
    }

    public function adoptedPets()
    {
        return $this->belongsToMany(Pet::class, 'adoptions', 'adopter_id', 'pet_id')
            ->wherePivot('status', 'approved');
    }

    public function chatsAsAdopter()
    {
        return $this->hasMany(Chat::class, 'adopter_id');
    }

    public function chatsAsOwner()
    {
        return $this->hasMany(Chat::class, 'owner_id');
    }

    public function allChats()
    {
        return Chat::where('adopter_id', $this->id)
            ->orWhere('owner_id', $this->id);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // Scopes para organizações
    public function scopeOrganizations($query)
    {
        return $query->where('user_type', UserType::ORGANIZATION);
    }

    public function scopeIndividuals($query)
    {
        return $query->where('user_type', UserType::INDIVIDUAL);
    }

    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    // Accessors
    public function getIsOrganizationAttribute()
    {
        return $this->user_type === UserType::ORGANIZATION;
    }

    public function getDisplayNameAttribute()
    {
        return $this->is_organization ? $this->organization_name : $this->name;
    }

    // Relationships para sistema de voluntários
    public function volunteers()
    {
        return $this->hasMany(OrganizationVolunteer::class, 'organization_id')
            ->where('active', true);
    }

    public function volunteerOrganizations()
    {
        return $this->hasMany(OrganizationVolunteer::class, 'volunteer_id')
            ->where('active', true);
    }

    public function organizationsAsVolunteer()
    {
        return $this->belongsToMany(User::class, 'organization_volunteers', 'volunteer_id', 'organization_id')
            ->wherePivot('active', true)
            ->withPivot(['role', 'permissions', 'joined_at']);
    }

    // Métodos de verificação de permissões
    public function canPerformActionInOrganization(int $organizationId, string $permission): bool
    {
        // Se é a própria organização
        if ($this->id === $organizationId && $this->is_organization) {
            return true;
        }

        // Verificar se é voluntário da organização com permissão
        $volunteer = $this->volunteerOrganizations()
            ->where('organization_id', $organizationId)
            ->first();

        if (! $volunteer) {
            return false;
        }

        return $volunteer->hasPermission($permission);
    }

    public function canManageVolunteers(int $organizationId): bool
    {
        return $this->canPerformActionInOrganization($organizationId, 'manage_volunteers');
    }

    public function canCreatePetsForOrganization(int $organizationId): bool
    {
        return $this->canPerformActionInOrganization($organizationId, 'create_pets');
    }

    public function isAdminOfOrganization(int $organizationId): bool
    {
        if ($this->id === $organizationId && $this->is_organization) {
            return true;
        }

        $volunteer = $this->volunteerOrganizations()
            ->where('organization_id', $organizationId)
            ->first();

        return $volunteer && $volunteer->role === \App\Enums\VolunteerRole::ADMIN;
    }
}
