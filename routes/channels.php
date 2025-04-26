<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return $user->chats()->where('chats.id', $chatId)->exists();
});
