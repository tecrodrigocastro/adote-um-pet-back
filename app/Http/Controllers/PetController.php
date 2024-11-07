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

        return response()->json($pets);
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

        return response()->json([
            'message' => 'Pet created successfully',
            'pet' => $pet,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet)
    {
        return response()->json($pet);
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

        return response()->json([
            'message' => 'Pet updated successfully',
            'pet' => $pet,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        $pet->delete();

        return response()->json([
            'message' => 'Pet deleted successfully',
        ]);
    }
}
