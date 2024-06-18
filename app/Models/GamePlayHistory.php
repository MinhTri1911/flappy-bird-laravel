<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePlayHistory extends Model
{
    protected $fillable = [
        'game_play_id', 'score', 'played_at', 'finished_at', 'scene', 'reward_issued_at'
    ];

    public function gamePlay(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GamePlay::class);
    }
}
