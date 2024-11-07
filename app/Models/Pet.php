<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pet extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'gender',
        'size',
        'birth_date',
        'breed',
        'color',
        'address',
        'description',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}


