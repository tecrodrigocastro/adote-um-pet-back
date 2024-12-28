<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRequest;
use App\Models\Chat;
use Illuminate\Http\Request;


class ChatController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/chats",
     *     summary="Get chat list",
     *     description="Retrieve a list of chats between adopters and pet owners based on the provided adopter, owner, and pet IDs. Returns chat details, including pet, adopter, and owner information, as well as messages.",
     *     tags={"Chats"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="adopter_id",
     *         in="query",
     *         required=true,
     *         description="ID of the adopter",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="owner_id",
     *         in="query",
     *         required=true,
     *         description="ID of the pet owner",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="pet_id",
     *         in="query",
     *         required=true,
     *         description="ID of the pet",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chat details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="pet", type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="name", type="string", example="Buddy"),
     *                 @OA\Property(property="photos", type="array",
     *                     @OA\Items(type="string", example="https://example.com/photo.jpg")
     *                 ),
     *                 @OA\Property(property="status", type="string", example="available")
     *             ),
     *             @OA\Property(property="adopter", type="object",
     *                 @OA\Property(property="id", type="integer", example=5),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="photo_url", type="string", example="https://example.com/johndoe.jpg"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com")
     *             ),
     *             @OA\Property(property="owner", type="object",
     *                 @OA\Property(property="id", type="integer", example=8),
     *                 @OA\Property(property="name", type="string", example="Jane Smith"),
     *                 @OA\Property(property="photo_url", type="string", example="https://example.com/janesmith.jpg"),
     *                 @OA\Property(property="email", type="string", example="jane.smith@example.com")
     *             ),
     *             @OA\Property(property="messages", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=101),
     *                     @OA\Property(property="content", type="string", example="Hello!"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-01T12:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No chat found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Nenhum chat encontrado!")
     *         )
     *     )
     * )
     */
    public function index(ChatRequest $request)
    {
        // 'adopter_id' => 'required|exists:users,id',
        // 'owner_id' => 'required|exists:users,id',
        // 'pet_id' => 'required|exists:pets,id',
        $data = $request->validated();
        $chats  = Chat::where('adopter_id', $data['adopter_id'])
            ->where('owner_id', $data['owner_id'])
            ->where('pet_id', $data['pet_id'])
            ->with('pet:id,name,photos,status')
            ->with('adopter:id,name,photo_url,email')
            ->with('owner:id,name,photo_url,email')
            ->with('messages')
            ->get();

        if ($chats->isEmpty()) {
            return $this->error('Nenhum chat encontrado!', 404);
        }

        return $this->success($chats);
    }

    /**
 * @OA\Post(
 *     path="/api/chats",
 *     summary="Create a new chat",
 *     description="Create a new chat record between an adopter and pet owner.",
 *     operationId="createChat",
 *     tags={"Chats"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"adopter_id", "owner_id", "pet_id"},
 *             @OA\Property(property="adopter_id", type="integer", example=5),
 *             @OA\Property(property="owner_id", type="integer", example=8),
 *             @OA\Property(property="pet_id", type="integer", example=10)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Chat created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Chat criado com sucesso!"),
 *             @OA\Property(property="chat", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="adopter_id", type="integer", example=5),
 *                 @OA\Property(property="owner_id", type="integer", example=8),
 *                 @OA\Property(property="pet_id", type="integer", example=10)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid input data.")
 *         )
 *     )
 * )
 */
    public function store(ChatRequest $request)
    {
        $request->validated();
        $chat = Chat::create($request->all());

        return $this->success($chat, 'Chat criado com sucesso!', 201);
    }
}
