<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Models\Pet;
use Illuminate\Http\Request;

/**
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     description="Enter token in format (Bearer <token>)",
 *     in="header",
 *     name="Authorization",
 *     bearerFormat="JWT",
 * )
 */
class PetController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pets",
     *     tags={"Pets"},
     *     summary="List all pets",
     *     description="Fetch all pets from the database with optional filters",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=false,
     *         description="Filter pets by type (e.g., Dog, Cat)",
     *
     *         @OA\Schema(type="string", example="Dog")
     *     ),
     *
     *     @OA\Parameter(
     *         name="gender",
     *         in="query",
     *         required=false,
     *         description="Filter pets by gender (e.g., Male, Female)",
     *
     *         @OA\Schema(type="string", example="Male")
     *     ),
     *
     *     @OA\Parameter(
     *         name="size",
     *         in="query",
     *         required=false,
     *         description="Filter pets by size (e.g., Small, Medium, Large)",
     *
     *         @OA\Schema(type="string", example="Medium")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved list of pets",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Busca realizada com sucesso!"),
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(
     *                     type="object",
     *
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Buddy"),
     *                     @OA\Property(property="type", type="string", example="Dog"),
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="gender", type="string", example="Male"),
     *                     @OA\Property(property="size", type="string", example="Medium"),
     *                     @OA\Property(property="birth_date", type="string", format="date", example="2021-04-01"),
     *                     @OA\Property(property="breed", type="string", example="Golden Retriever"),
     *                     @OA\Property(property="color", type="string", example="Golden"),
     *                     @OA\Property(property="description", type="string", example="A friendly and playful dog."),
     *                     @OA\Property(property="photos", type="array",
     *
     *                         @OA\Items(type="string", example="https://example.com/photo1.jpg")
     *                     ),
     *
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-01T10:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-01T10:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 20);
        $perPage = min($perPage, 100); // Máximo de 100 por página

        $pets = Pet::query()
            ->with(['user:id,name'])
            ->where('status', 'unadopted');

        if ($request->has('type')) {
            $pets->where('type', $request->type);
        }
        if ($request->has('gender')) {
            $pets->where('gender', $request->gender);
        }

        if ($request->has('size')) {
            $pets->where('size', $request->size);
        }

        $paginatedPets = $pets->paginate($perPage);

        return $this->success(
            $paginatedPets,
            'Busca realizada com sucesso!',
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/pets",
     *     tags={"Pets"},
     *     summary="Create a new pet",
     *     description="Create a new pet and store it in the database with image file uploads",
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pet registration data with image files",
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 required={"name", "type", "gender", "size", "description", "photos"},
     *
     *                 @OA\Property(property="name", type="string", example="Buddy"),
     *                 @OA\Property(property="type", type="string", example="Dog"),
     *                 @OA\Property(property="gender", type="string", example="Male"),
     *                 @OA\Property(property="size", type="string", example="Medium"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="2021-04-01"),
     *                 @OA\Property(property="breed", type="string", example="Golden Retriever"),
     *                 @OA\Property(property="color", type="string", example="Golden"),
     *                 @OA\Property(property="description", type="string", example="A friendly and playful dog."),
     *                 @OA\Property(
     *                     property="photos",
     *                     type="array",
     *                     description="Array of image files (1-5 images, max 5MB each)",
     *
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Pet successfully created",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pet cadastrado com sucesso!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Buddy"),
     *                 @OA\Property(property="type", type="string", example="Dog"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="gender", type="string", example="Male"),
     *                 @OA\Property(property="size", type="string", example="Medium"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="2021-04-01"),
     *                 @OA\Property(property="breed", type="string", example="Golden Retriever"),
     *                 @OA\Property(property="color", type="string", example="Golden"),
     *                 @OA\Property(property="description", type="string", example="A friendly and playful dog."),
     *                 @OA\Property(property="photos", type="array",
     *
     *                     @OA\Items(type="string", example="https://example.com/photo1.jpg")
     *                 ),
     *
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-01T10:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-01T10:00:00Z")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Dados inválidos."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function store(StorePetRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();

        // Processar upload das imagens
        $photoPaths = [];

        if ($request->hasFile('photos')) {
            // Criar nome da pasta: {id}_{nome_do_usuario}
            $folderName = $user->id . '_' . str_replace(' ', '_', $user->name);

            foreach ($request->file('photos') as $index => $photo) {
                // Gerar nome único para o arquivo
                $filename = time() . '_' . $index . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();

                // Salvar na storage/app/public/pets/{folderName}/
                $path = $photo->storeAs("pets/{$folderName}", $filename, 'public');

                // Adicionar URL completa ao array
                $photoPaths[] = asset('storage/' . $path);
            }
        }

        // Substituir o array de arquivos pelos paths salvos
        $data['photos'] = $photoPaths;

        // Garantir que user_id seja o usuário autenticado
        $data['user_id'] = $user->id;

        $pet = Pet::create($data);

        return $this->success(
            $pet->load('user:id,name'),
            'Pet cadastrado com sucesso!',
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/api/pets/{id}",
     *     tags={"Pets"},
     *     summary="Display a specific pet",
     *     description="Fetch a pet by its ID from the database",
     *  security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the pet to retrieve",
     *         required=true,
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved the pet",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Busca realizada com sucesso!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Buddy"),
     *                 @OA\Property(property="type", type="string", example="Dog"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="gender", type="string", example="Male"),
     *                 @OA\Property(property="size", type="string", example="Medium"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="2021-04-01"),
     *                 @OA\Property(property="breed", type="string", example="Golden Retriever"),
     *                 @OA\Property(property="color", type="string", example="Golden"),
     *                 @OA\Property(property="description", type="string", example="A friendly and playful dog."),
     *                 @OA\Property(property="photos", type="array",
     *
     *                     @OA\Items(type="string", example="https://example.com/photo1.jpg")
     *                 ),
     *
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-01T10:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-01T10:00:00Z")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Pet not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pet não encontrado."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function show(Pet $pet)
    {
        return $this->success($pet, 'Busca realizada com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pet $pet) {}

    /**
     * @OA\Post(
     *     path="/api/pets/{id}/photos",
     *     tags={"Pets"},
     *     summary="Update photos of a pet",
     *     description="Upload and update the photos of a specific pet.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the pet whose photos are to be updated.",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Array of photo files to upload",
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(
     *                     property="photos",
     *                     type="array",
     *
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Photos updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Fotos atualizadas com sucesso!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Buddy"),
     *                 @OA\Property(property="photos", type="array",
     *
     *                     @OA\Items(type="string", example="pets/photo1.jpg")
     *                 ),
     *
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-01T10:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-02T10:00:00Z")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, no photos provided",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No photos provided."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Pet not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pet not found."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function updatePhotos(Pet $pet, Request $request)
    {
        if (! $request->hasFile('photos')) {
            return $this->error('Nenhuma foto foi enviada!', 400);
        }

        $user = auth()->user();
        $photoPaths = [];

        // Criar nome da pasta: {id}_{nome_do_usuario}
        $folderName = $user->id . '_' . str_replace(' ', '_', $user->name);

        foreach ($request->file('photos') as $index => $photo) {
            // Gerar nome único para o arquivo
            $filename = time() . '_' . $index . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();

            // Salvar na storage/app/public/pets/{folderName}/
            $path = $photo->storeAs("pets/{$folderName}", $filename, 'public');

            // Adicionar URL completa ao array
            $photoPaths[] = asset('storage/' . $path);
        }

        $pet->photos = $photoPaths;
        $pet->save();

        return $this->success($pet, 'Fotos atualizadas com sucesso!');
    }

    /**
     * @OA\Put(
     *     path="/api/pets/{id}",
     *     tags={"Pets"},
     *     summary="Update a pet",
     *     description="Update the details of an existing pet",
     *  security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the pet to update",
     *         required=true,
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name", "type", "user_id", "gender", "size", "description", "photos"},
     *
     *             @OA\Property(property="name", type="string", example="Buddy"),
     *             @OA\Property(property="type", type="string", example="Dog"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="gender", type="string", example="Male"),
     *             @OA\Property(property="size", type="string", example="Medium"),
     *             @OA\Property(property="birth_date", type="string", format="date", example="2021-04-01"),
     *             @OA\Property(property="breed", type="string", example="Golden Retriever"),
     *             @OA\Property(property="color", type="string", example="Golden"),
     *             @OA\Property(property="description", type="string", example="A friendly and playful dog."),
     *             @OA\Property(property="photos", type="array",
     *
     *                 @OA\Items(type="string", example="https://example.com/photo1.jpg")
     *             )
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Pet updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pet atualizado com sucesso!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Buddy"),
     *                 @OA\Property(property="type", type="string", example="Dog"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="gender", type="string", example="Male"),
     *                 @OA\Property(property="size", type="string", example="Medium"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="2021-04-01"),
     *                 @OA\Property(property="breed", type="string", example="Golden Retriever"),
     *                 @OA\Property(property="color", type="string", example="Golden"),
     *                 @OA\Property(property="description", type="string", example="A friendly and playful dog."),
     *                 @OA\Property(property="photos", type="array",
     *
     *                     @OA\Items(type="string", example="https://example.com/photo1.jpg")
     *                 ),
     *
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-01T10:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-01T10:00:00Z")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Dados inválidos."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $data = $request->validated();

        $pet->update($data);

        return $this->success($pet, 'Pet atualizado com sucesso!');
    }

    /**
     * @OA\Delete(
     *     path="/api/pets/{id}",
     *     tags={"Pets"},
     *     summary="Delete a pet",
     *     description="Remove a pet from the database",
     *  security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the pet to delete",
     *         required=true,
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successfully deleted the pet",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pet deletado com sucesso!"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Pet not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pet não encontrado."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function destroy(Pet $pet)
    {
        $pet->delete();

        return $this->success([], 'Pet deletado com sucesso!');
    }
}
