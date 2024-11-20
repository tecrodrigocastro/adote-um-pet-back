<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Models\Pet;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pets = Pet::all();

        return $this->success($pets, 'Busca realizada com sucesso!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetRequest $request)
    {
        $data = $request->validated();

        $pet = Pet::create($data);

        return $this->success(
            $pet,
            'Pet cadastrado com sucesso!',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet)
    {
        return $this->success($pet, 'Busca realizada com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pet $pet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $data = $request->validated();

        $pet->update($data);

        return $this->success($pet, 'Pet atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        $pet->delete();

        return $this->success([], 'Pet deletado com sucesso!');
    }
}
