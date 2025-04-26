<?php

namespace App\Http\Controllers\Message;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    /**
 * @OA\Post(
 *     path="/api/messages",
 *     summary="Send a new message",
 *     description="Send a new message in a chat session.",
 *     operationId="sendMessage",
 *     tags={"Messages"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"chat_id", "sender_id", "content"},
 *             @OA\Property(property="chat_id", type="integer", example=1),
 *             @OA\Property(property="sender_id", type="integer", example=5),
 *             @OA\Property(property="content", type="string", example="Hello, how are you?")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Message created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="chat_id", type="integer", example=1),
 *                 @OA\Property(property="sender_id", type="integer", example=5),
 *                 @OA\Property(property="content", type="string", example="Hello, how are you?"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-25T15:30:00Z")
 *             ),
 *             @OA\Property(property="is_me", type="boolean", example=true)
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
    public function store(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'sender_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $chat = Chat::findOrFail($request->chat_id);
        if ($chat->adopter_id !== Auth::id() && $chat->owner_id !== Auth::id()) {
            return $this->error('Não autorizado', 403);
        }

        $user = $request->user();
        $is_me = $user->id == $request->sender_id;

        $message = Message::create($request->all());

        $chat->touch('updated_at');

        broadcast(new MessageSent($message))->toOthers();

        return $this->success([
            'message' => $message,
            'is_me' => $is_me
        ], 'Message created successfully', 201);
    }
}
