<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Requests\RegisterOrganizationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    /**
     * Display a listing of organizations.
     */
    public function index(Request $request)
    {
        $query = User::organizations()->verified();

        // Filtros
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('organization_name', 'like', "%{$search}%")
                    ->orWhere('mission_statement', 'like', "%{$search}%");
            });
        }

        if ($request->has('city')) {
            $query->whereHas('addresses', function ($q) use ($request) {
                $q->where('city', 'like', "%{$request->city}%");
            });
        }

        if ($request->has('state')) {
            $query->whereHas('addresses', function ($q) use ($request) {
                $q->where('state', $request->state);
            });
        }

        $organizations = $query->with(['addresses'])
            ->paginate($request->input('per_page', 20));

        return $this->success($organizations, 'Organizações listadas com sucesso!');
    }

    /**
     * Register a new organization.
     */
    public function register(RegisterOrganizationRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Separar dados da organização e endereço
            $organizationData = [
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'user_type' => UserType::ORGANIZATION,
                'organization_name' => $data['organization_name'],
                'name' => $data['organization_name'], // Para compatibilidade
                'cnpj' => $data['cnpj'],
                'responsible_name' => $data['responsible_name'],
                'phone' => $data['phone'],
                'mission_statement' => $data['mission_statement'],
                'website' => $data['website'] ?? null,
                'social_media' => $data['social_media'] ?? null,
                'verified' => false, // Aguardando verificação
            ];

            $addressData = $data['address'];

            // Criar organização
            $organization = User::create($organizationData);

            // Criar endereço
            $organization->addresses()->create($addressData);

            // Carregar com relacionamentos
            $organization->load('addresses');

            DB::commit();

            return $this->success($organization, 'Organização cadastrada com sucesso! Aguardando verificação.', 201);

        } catch (\Exception $e) {
            DB::rollback();

            return $this->error('Erro ao cadastrar organização: '.$e->getMessage(), 400);
        }
    }

    /**
     * Display the specified organization.
     */
    public function show(User $organization)
    {
        if (! $organization->is_organization) {
            return $this->error('Usuário não é uma organização', 404);
        }

        $organization->load([
            'addresses',
            'pets' => function ($query) {
                $query->where('status', 'unadopted')->take(10);
            },
        ]);

        // Dados públicos da organização
        $publicData = [
            'id' => $organization->id,
            'organization_name' => $organization->organization_name,
            'mission_statement' => $organization->mission_statement,
            'website' => $organization->website,
            'social_media' => $organization->social_media,
            'verified' => $organization->verified,
            'verified_at' => $organization->verified_at,
            'addresses' => $organization->addresses,
            'pets_count' => $organization->pets()->count(),
            'recent_pets' => $organization->pets,
            'created_at' => $organization->created_at,
        ];

        return $this->success($publicData, 'Organização recuperada com sucesso!');
    }

    /**
     * Update organization profile.
     */
    public function update(Request $request, User $organization)
    {
        if (! $organization->is_organization) {
            return $this->error('Usuário não é uma organização', 404);
        }

        // Verificar se o usuário pode editar esta organização
        if (! $this->canManageOrganization($organization)) {
            return $this->error('Não autorizado', 403);
        }

        $validated = $request->validate([
            'organization_name' => 'sometimes|required|string|max:255',
            'responsible_name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:255',
            'mission_statement' => 'sometimes|required|string|max:1000',
            'website' => 'nullable|url',
            'social_media' => 'nullable|array',
            'social_media.facebook' => 'nullable|url',
            'social_media.instagram' => 'nullable|url',
            'social_media.twitter' => 'nullable|url',
            'social_media.linkedin' => 'nullable|url',
        ]);

        // Atualizar nome também para compatibilidade
        if (isset($validated['organization_name'])) {
            $validated['name'] = $validated['organization_name'];
        }

        $organization->update($validated);
        $organization->load('addresses');

        return $this->success($organization, 'Organização atualizada com sucesso!');
    }

    /**
     * Get organization statistics.
     */
    public function statistics(User $organization)
    {
        if (! $organization->is_organization) {
            return $this->error('Usuário não é uma organização', 404);
        }

        if (! $this->canViewOrganizationStats($organization)) {
            return $this->error('Não autorizado', 403);
        }

        $stats = [
            'total_pets' => $organization->pets()->count(),
            'available_pets' => $organization->pets()->where('status', 'unadopted')->count(),
            'adopted_pets' => $organization->pets()->where('status', 'adopted')->count(),
            'pending_adoptions' => $organization->receivedAdoptionRequests()->where('status', 'pending')->count(),
            'approved_adoptions' => $organization->receivedAdoptionRequests()->where('status', 'approved')->count(),
            'volunteers_count' => $organization->volunteers()->count(),
            'active_chats' => $organization->chatsAsOwner()->count(),
        ];

        return $this->success($stats, 'Estatísticas recuperadas com sucesso!');
    }

    /**
     * Verify organization (admin only).
     */
    public function verify(User $organization)
    {
        // TODO: Implementar middleware para admin
        if (! $organization->is_organization) {
            return $this->error('Usuário não é uma organização', 404);
        }

        $organization->update([
            'verified' => true,
            'verified_at' => now(),
        ]);

        return $this->success($organization, 'Organização verificada com sucesso!');
    }

    /**
     * List organization pets.
     */
    public function pets(User $organization, Request $request)
    {
        if (! $organization->is_organization) {
            return $this->error('Usuário não é uma organização', 404);
        }

        $query = $organization->pets();

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $pets = $query->paginate($request->input('per_page', 20));

        return $this->success($pets, 'Pets da organização listados com sucesso!');
    }

    /**
     * Check if current user can manage organization.
     */
    private function canManageOrganization(User $organization): bool
    {
        $user = Auth::user();

        // Se é a própria organização
        if ($user->id === $organization->id) {
            return true;
        }

        // Se é admin ou manager da organização
        return $user->canPerformActionInOrganization($organization->id, 'configure_organization');
    }

    /**
     * Check if current user can view organization stats.
     */
    private function canViewOrganizationStats(User $organization): bool
    {
        $user = Auth::user();

        // Se é a própria organização
        if ($user->id === $organization->id) {
            return true;
        }

        // Se é voluntário com permissão
        return $user->canPerformActionInOrganization($organization->id, 'view_reports');
    }
}
