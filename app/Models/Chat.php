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
}
