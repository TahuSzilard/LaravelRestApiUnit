<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'title',
        'director_id',
        'release_date',
        'description',
        'image',
        'type_id',
        'length',
        'created_at',
        'updated_at'
    ];

    public function director()
    {
        return $this->belongsTo(Director::class, 'director_id');
    }
    
    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'film_actor', 'film_id', 'actor_id')->withPivot('is_lead')->withTimestamps(); 
    }

}