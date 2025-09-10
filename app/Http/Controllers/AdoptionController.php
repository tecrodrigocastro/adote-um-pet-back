<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdoptionRequest;
use App\Models\Adoption;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdoptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adoptions = Adoption::with(['pet', 'adopter', 'owner'])
            ->where('adopter_id', Auth::id())
            ->orWhereHas('pet', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->paginate(20);

        return $this->success($adoptions, 'Adoções recuperadas com sucesso!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdoptionRequest $request, Pet $pet)
    {
        DB::beginTransaction();

        try {
            // Criar solicitação de adoção
            $adoption = Adoption::create([
                'adopter_id' => Auth::id(),
                'pet_id' => $pet->id,
                'owner_id' => $pet->user_id,
                'status' => 'pending',
                'message' => $request->message,
            ]);

            // Criar chat automaticamente
            $chat = Chat::firstOrCreate([
                'adopter_id' => Auth::id(),
                'owner_id' => $pet->user_id,
                'pet_id' => $pet->id,
            ]);

            // Primeira mensagem no chat
            if ($request->message) {
                Message::create([
                    'chat_id' => $chat->id,
                    'user_id' => Auth::id(),
                    'message' => $request->message,
                ]);
            }

            // Atualizar status do pet para pending se estiver available
            if ($pet->status === 'unadopted') {
                $pet->update(['status' => 'pending']);
            }

            DB::commit();

            return $this->success([
                'adoption' => $adoption->load(['pet', 'adopter', 'owner']),
                'chat' => $chat,
            ], 'Solicitação de adoção enviada com sucesso!', 201);

        } catch (\Exception $e) {
            DB::rollback();

            return $this->error('Erro ao processar solicitação de adoção: '.$e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Adoption $adoption)
    {
        // Verificar se o usuário tem permissão para ver esta adoção
        if ($adoption->adopter_id !== Auth::id() && $adoption->owner_id !== Auth::id()) {
            return $this->error('Não autorizado', 403);
        }

        $adoption->load(['pet', 'adopter', 'owner']);

        return $this->success($adoption, 'Adoção recuperada com sucesso!');
    }

    /**
     * Approve an adoption request
     */
    public function approve(Adoption $adoption)
    {
        // Verificar se o usuário é o proprietário do pet
        if ($adoption->owner_id !== Auth::id()) {
            return $this->error('Apenas o proprietário pode aprovar a adoção', 403);
        }

        if ($adoption->status !== 'pending') {
            return $this->error('Esta adoção não pode ser aprovada', 400);
        }

        DB::beginTransaction();

        try {
            // Aprovar esta adoção
            $adoption->update([
                'status' => 'approved',
                'adoption_date' => now(),
            ]);

            // Rejeitar outras solicitações para o mesmo pet
            Adoption::where('pet_id', $adoption->pet_id)
                ->where('id', '!=', $adoption->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);

            // Marcar pet como adotado
            $adoption->pet->update(['status' => 'adopted']);

            DB::commit();

            return $this->success(
                $adoption->load(['pet', 'adopter', 'owner']),
                'Adoção aprovada com sucesso!'
            );

        } catch (\Exception $e) {
            DB::rollback();

            return $this->error('Erro ao aprovar adoção: '.$e->getMessage(), 500);
        }
    }

    /**
     * Reject an adoption request
     */
    public function reject(Adoption $adoption)
    {
        // Verificar se o usuário é o proprietário do pet
        if ($adoption->owner_id !== Auth::id()) {
            return $this->error('Apenas o proprietário pode rejeitar a adoção', 403);
        }

        if ($adoption->status !== 'pending') {
            return $this->error('Esta adoção não pode ser rejeitada', 400);
        }

        $adoption->update(['status' => 'rejected']);

        // Se não há mais adoções pendentes, voltar pet para disponível
        $pendingAdoptions = Adoption::where('pet_id', $adoption->pet_id)
            ->where('status', 'pending')
            ->count();

        if ($pendingAdoptions === 0) {
            $adoption->pet->update(['status' => 'unadopted']);
        }

        return $this->success(
            $adoption->load(['pet', 'adopter', 'owner']),
            'Adoção rejeitada'
        );
    }

    /**
     * Get adoption requests received by the authenticated user
     */
    public function receivedRequests()
    {
        $adoptions = Adoption::whereHas('pet', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['pet', 'adopter'])
            ->where('status', 'pending')
            ->paginate(20);

        return $this->success($adoptions, 'Solicitações recebidas recuperadas com sucesso!');
    }

    /**
     * Get adoption requests made by the authenticated user
     */
    public function myRequests()
    {
        $adoptions = Adoption::where('adopter_id', Auth::id())
            ->with(['pet', 'owner'])
            ->paginate(20);

        return $this->success($adoptions, 'Minhas solicitações recuperadas com sucesso!');
    }
}
