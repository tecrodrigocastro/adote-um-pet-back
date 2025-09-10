<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of the user's addresses.
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()->paginate(10);

        return $this->success($addresses, 'Endereços recuperados com sucesso!');
    }

    /**
     * Store a newly created address.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'street' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'number_house' => 'required|integer',
            'complement' => 'nullable|string|max:255',
            'zip_code' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:2',
        ]);

        $address = Auth::user()->addresses()->create($validated);

        return $this->success($address, 'Endereço criado com sucesso!', 201);
    }

    /**
     * Display the specified address.
     */
    public function show(Address $address)
    {
        // Verificar se o endereço pertence ao usuário autenticado
        if ($address->user_id !== Auth::id()) {
            return $this->error('Endereço não encontrado', 404);
        }

        return $this->success($address, 'Endereço recuperado com sucesso!');
    }

    /**
     * Update the specified address.
     */
    public function update(Request $request, Address $address)
    {
        // Verificar se o endereço pertence ao usuário autenticado
        if ($address->user_id !== Auth::id()) {
            return $this->error('Endereço não encontrado', 404);
        }

        $validated = $request->validate([
            'street' => 'sometimes|required|string|max:255',
            'neighborhood' => 'sometimes|required|string|max:255',
            'number_house' => 'sometimes|required|integer',
            'complement' => 'nullable|string|max:255',
            'zip_code' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'state' => 'sometimes|required|string|max:2',
        ]);

        $address->update($validated);

        return $this->success($address, 'Endereço atualizado com sucesso!');
    }

    /**
     * Remove the specified address.
     */
    public function destroy(Address $address)
    {
        // Verificar se o endereço pertence ao usuário autenticado
        if ($address->user_id !== Auth::id()) {
            return $this->error('Endereço não encontrado', 404);
        }

        // Verificar se não é o único endereço do usuário
        $addressCount = Auth::user()->addresses()->count();
        if ($addressCount <= 1) {
            return $this->error('Não é possível excluir o único endereço', 400);
        }

        $address->delete();

        return $this->success([], 'Endereço removido com sucesso!');
    }
}
