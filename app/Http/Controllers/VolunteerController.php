<?php

namespace App\Http\Controllers;

use App\Enums\VolunteerRole;
use App\Models\OrganizationVolunteer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VolunteerController extends Controller
{
    /**
     * Display volunteers of an organization.
     */
    public function index(User $organization, Request $request)
    {
        if (! $organization->is_organization) {
            return $this->error('Usuário não é uma organização', 404);
        }

        // Verificar permissão
        if (! Auth::user()->canManageVolunteers($organization->id) && Auth::id() !== $organization->id) {
            return $this->error('Não autorizado', 403);
        }

        $query = $organization->volunteers();

        // Filtros
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('active')) {
            $query->where('active', $request->boolean('active'));
        }

        $volunteers = $query->with(['volunteer:id,name,email,phone'])
            ->paginate($request->input('per_page', 20));

        return $this->success($volunteers, 'Voluntários listados com sucesso!');
    }

    /**
     * Invite a volunteer to organization.
     */
    public function invite(User $organization, Request $request)
    {
        if (! $organization->is_organization) {
            return $this->error('Usuário não é uma organização', 404);
        }

        // Verificar permissão
        if (! Auth::user()->canManageVolunteers($organization->id) && Auth::id() !== $organization->id) {
            return $this->error('Não autorizado', 403);
        }

        $validated = $request->validate([
            'volunteer_id' => 'required|exists:users,id',
            'role' => ['required', Rule::in(VolunteerRole::values())],
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        // Verificar se o usuário não é uma organização
        $volunteer = User::findOrFail($validated['volunteer_id']);
        if ($volunteer->is_organization) {
            return $this->error('Organizações não podem ser voluntárias', 400);
        }

        // Verificar se já não é voluntário
        $existingVolunteer = OrganizationVolunteer::where('organization_id', $organization->id)
            ->where('volunteer_id', $validated['volunteer_id'])
            ->first();

        if ($existingVolunteer) {
            if ($existingVolunteer->active) {
                return $this->error('Usuário já é voluntário desta organização', 400);
            } else {
                // Reativar voluntário
                $existingVolunteer->update([
                    'role' => $validated['role'],
                    'permissions' => $validated['permissions'] ?? null,
                    'active' => true,
                    'joined_at' => now(),
                ]);

                $existingVolunteer->load(['volunteer:id,name,email']);

                return $this->success($existingVolunteer, 'Voluntário reativado com sucesso!');
            }
        }

        // Criar novo voluntário
        $organizationVolunteer = OrganizationVolunteer::create([
            'organization_id' => $organization->id,
            'volunteer_id' => $validated['volunteer_id'],
            'role' => $validated['role'],
            'permissions' => $validated['permissions'] ?? null,
            'active' => true,
            'joined_at' => now(),
        ]);

        $organizationVolunteer->load(['volunteer:id,name,email']);

        return $this->success($organizationVolunteer, 'Voluntário adicionado com sucesso!', 201);
    }

    /**
     * Update volunteer role and permissions.
     */
    public function update(User $organization, OrganizationVolunteer $volunteer, Request $request)
    {
        if (! $organization->is_organization) {
            return $this->error('Usuário não é uma organização', 404);
        }

        // Verificar se o voluntário pertence à organização
        if ($volunteer->organization_id !== $organization->id) {
            return $this->error('Voluntário não pertence a esta organização', 404);
        }

        // Verificar permissão
        if (! Auth::user()->canManageVolunteers($organization->id) && Auth::id() !== $organization->id) {
            return $this->error('Não autorizado', 403);
        }

        // Não pode alterar o próprio papel se for o único admin
        if ($volunteer->volunteer_id === Auth::id() && $volunteer->role === VolunteerRole::ADMIN) {
            $adminCount = OrganizationVolunteer::where('organization_id', $organization->id)
                ->where('role', VolunteerRole::ADMIN)
                ->where('active', true)
                ->count();

            if ($adminCount <= 1 && $request->role !== VolunteerRole::ADMIN->value) {
                return $this->error('Não é possível alterar o papel do último administrador', 400);
            }
        }

        $validated = $request->validate([
            'role' => ['sometimes', 'required', Rule::in(VolunteerRole::values())],
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $volunteer->update($validated);
        $volunteer->load(['volunteer:id,name,email']);

        return $this->success($volunteer, 'Voluntário atualizado com sucesso!');
    }

    /**
     * Remove volunteer from organization.
     */
    public function destroy(User $organization, OrganizationVolunteer $volunteer)
    {
        if (! $organization->is_organization) {
            return $this->error('Usuário não é uma organização', 404);
        }

        // Verificar se o voluntário pertence à organização
        if ($volunteer->organization_id !== $organization->id) {
            return $this->error('Voluntário não pertence a esta organização', 404);
        }

        // Verificar permissão
        if (! Auth::user()->canManageVolunteers($organization->id) && Auth::id() !== $organization->id) {
            return $this->error('Não autorizado', 403);
        }

        // Não pode remover o último admin
        if ($volunteer->role === VolunteerRole::ADMIN) {
            $adminCount = OrganizationVolunteer::where('organization_id', $organization->id)
                ->where('role', VolunteerRole::ADMIN)
                ->where('active', true)
                ->count();

            if ($adminCount <= 1) {
                return $this->error('Não é possível remover o último administrador', 400);
            }
        }

        $volunteer->update(['active' => false]);

        return $this->success([], 'Voluntário removido com sucesso!');
    }

    /**
     * List organizations where user is volunteer.
     */
    public function myOrganizations(Request $request)
    {
        $user = Auth::user();

        if ($user->is_organization) {
            return $this->error('Organizações não podem ser voluntárias', 400);
        }

        $organizations = $user->organizationsAsVolunteer()
            ->with(['addresses'])
            ->paginate($request->input('per_page', 20));

        return $this->success($organizations, 'Minhas organizações listadas com sucesso!');
    }

    /**
     * Show volunteer details in organization.
     */
    public function show(User $organization, OrganizationVolunteer $volunteer)
    {
        if (! $organization->is_organization) {
            return $this->error('Usuário não é uma organização', 404);
        }

        // Verificar se o voluntário pertence à organização
        if ($volunteer->organization_id !== $organization->id) {
            return $this->error('Voluntário não pertence a esta organização', 404);
        }

        // Verificar permissão (pode ver se for da organização ou o próprio voluntário)
        if (! Auth::user()->canPerformActionInOrganization($organization->id, 'manage_volunteers')
            && Auth::id() !== $organization->id
            && Auth::id() !== $volunteer->volunteer_id) {
            return $this->error('Não autorizado', 403);
        }

        $volunteer->load(['volunteer:id,name,email,phone']);

        return $this->success($volunteer, 'Detalhes do voluntário recuperados com sucesso!');
    }

    /**
     * Leave organization as volunteer.
     */
    public function leave(User $organization)
    {
        if (! $organization->is_organization) {
            return $this->error('Usuário não é uma organização', 404);
        }

        $user = Auth::user();

        $volunteer = OrganizationVolunteer::where('organization_id', $organization->id)
            ->where('volunteer_id', $user->id)
            ->where('active', true)
            ->first();

        if (! $volunteer) {
            return $this->error('Você não é voluntário desta organização', 404);
        }

        // Verificar se não é o último admin
        if ($volunteer->role === VolunteerRole::ADMIN) {
            $adminCount = OrganizationVolunteer::where('organization_id', $organization->id)
                ->where('role', VolunteerRole::ADMIN)
                ->where('active', true)
                ->count();

            if ($adminCount <= 1) {
                return $this->error('Não é possível sair sendo o último administrador', 400);
            }
        }

        $volunteer->update(['active' => false]);

        return $this->success([], 'Você saiu da organização com sucesso!');
    }
}
