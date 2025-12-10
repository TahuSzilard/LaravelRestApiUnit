<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Film;

class Director extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = 
    [
        'name',
        'created_at',
        'updated_at'
    ];
    public function films()
    {
        return $this->hasMany(Film::class, 'director_id');
    }
}