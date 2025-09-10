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
    public function markAsRead(Message $message)
    {
        $chat = $message->chat;
        
        if (!$chat->hasParticipant(Auth::id())) {
            return $this->error('Não autorizado', 403);
        }
        
        if (!$message->isSentBy(Auth::id())) {
            $message->markAsRead();
        }
        
        return $this->success(null, 'Mensagem marcada como lida');
    }
    
    public function markChatAsRead(Chat $chat)
    {
        if (!$chat->hasParticipant(Auth::id())) {
            return $this->error('Não autorizado', 403);
        }
        
        $chat->messages()
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return $this->success(null, 'Mensagens marcadas como lidas');
    }
    /**
     * @OA\Post(
     *     path="/api/messages",
     *     summary="Send a new message",
     *     description="Send a new message in a chat session.",
     *     operationId="sendMessage",
     *     tags={"Messages"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"chat_id", "content"},
     *
     *             @OA\Property(property="chat_id", type="integer", example=1),
     *             @OA\Property(property="content", type="string", example="Hello, how are you?")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Message created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="chat_id", type="integer", example=1),
     *                 @OA\Property(property="content", type="string", example="Hello, how are you?"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-25T15:30:00Z")
     *             ),
     *             @OA\Property(property="is_me", type="boolean", example=true)
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
    public function store(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'content' => 'required|string|max:1000',
        ]);

        $chat = Chat::findOrFail($request->chat_id);
        
        if ($chat->adopter_id !== Auth::id() && $chat->owner_id !== Auth::id()) {
            return $this->error('Não autorizado', 403);
        }

        $message = Message::create([
            'chat_id' => $request->chat_id,
            'sender_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $chat->touch('updated_at');

        broadcast(new MessageSent($message))->toOthers();

        return $this->success([
            'message' => $message,
            'is_me' => true,
        ], 'Message created successfully', 201);
    }
}
