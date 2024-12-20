<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photographer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email'];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_photographers');
    }
}
