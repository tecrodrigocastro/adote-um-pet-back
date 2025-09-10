<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adoption extends Model
{
    /** @use HasFactory<\Database\Factories\AdoptionFactory> */
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'adopter_id',
        'owner_id',
        'adoption_date',
        'status',
        'message',
    ];

    protected $dates = [
        'adoption_date',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function adopter()
    {
        return $this->belongsTo(User::class, 'adopter_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
