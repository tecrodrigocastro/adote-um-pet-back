<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRequest;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/chats",
     *     summary="Get chat list",
     *     description="Retrieve a list of chats for the authenticated user. Returns chat details, including pet, adopter, and owner information, as well as the latest message.",
     *     tags={"Chats"},
     *     security={{"sanctum":{}}},
     *
     *
     *     @OA\Response(
     *         response=200,
     *         description="Chat details retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="pet", type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="name", type="string", example="Buddy"),
     *                 @OA\Property(property="photos", type="array",
     *
     *                     @OA\Items(type="string", example="https://example.com/photo.jpg")
     *                 ),
     *
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
     *
     *                 @OA\Items(
     *
     *                     @OA\Property(property="id", type="integer", example=101),
     *                     @OA\Property(property="content", type="string", example="Hello!"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-01T12:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No chat found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Nenhum chat encontrado!")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $chats = Chat::where(function ($query) {
            $query->where('adopter_id', Auth::id())
                ->orWhere('owner_id', Auth::id());
        })
            ->with([
                'pet:id,name,photos,status',
                'adopter:id,name,photo_url,email',
                'owner:id,name,photo_url,email',
                'messages' => function ($query) {
                    $query->latest()->take(1);
                },
            ])
            ->orderBy('updated_at', 'desc')
            ->get();

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
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"adopter_id", "owner_id", "pet_id"},
     *
     *             @OA\Property(property="adopter_id", type="integer", example=5),
     *             @OA\Property(property="owner_id", type="integer", example=8),
     *             @OA\Property(property="pet_id", type="integer", example=10)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Chat created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Chat criado com sucesso!"),
     *             @OA\Property(property="chat", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="adopter_id", type="integer", example=5),
     *                 @OA\Property(property="owner_id", type="integer", example=8),
     *                 @OA\Property(property="pet_id", type="integer", example=10)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Invalid input data.")
     *         )
     *     )
     * )
     */
    public function store(ChatRequest $request)
    {
        $data = $request->validated();
        $existingChat = Chat::where('pet_id', $data['pet_id'])
            ->where(function ($query) use ($data) {
                $query->where(function ($q) use ($data) {
                    $q->where('adopter_id', $data['adopter_id'])
                        ->where('owner_id', $data['owner_id']);
                })->orWhere(function ($q) use ($data) {
                    $q->where('adopter_id', $data['owner_id'])
                        ->where('owner_id', $data['adopter_id']);
                });
            })
            ->first();

        if ($existingChat) {
            return $this->success($existingChat, 'Chat já existe');
        }

        $chat = Chat::create($data);

        return $this->success($chat, 'Chat criado com sucesso!', 201);
    }

    public function show(Chat $chat)
    {
        if ($chat->adopter_id !== Auth::id() && $chat->owner_id !== Auth::id()) {
            return $this->error('Não autorizado', 403);
        }

        $chat->load(['pet', 'adopter', 'owner']);

        $messages = $chat->messages()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return $this->success([
            'chat' => $chat,
            'messages' => $messages,
        ]);
    }
}
