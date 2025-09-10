<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'adopter_id',
        'owner_id',
        'pet_id',
    ];

    public function adopter()
    {
        return $this->belongsTo(User::class, 'adopter_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class, 'chat_id')->latest();
    }

    public function hasParticipant(int $userId): bool
    {
        return $this->adopter_id === $userId || $this->owner_id === $userId;
    }

    public function getOtherParticipant(int $userId)
    {
        if ($this->adopter_id === $userId) {
            return $this->owner;
        }
        
        if ($this->owner_id === $userId) {
            return $this->adopter;
        }
        
        return null;
    }
}
