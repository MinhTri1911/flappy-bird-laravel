<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'setting'
    ];

    protected $casts = [
        'setting' => 'json',
    ];

    public function gamePlays(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GamePlay::class);
    }
}
