<?php

namespace App\DAL\Repositories;

use App\DAL\Interfaces\GamePlayRepositoryInterface;
use App\Models\GamePlay;
use Illuminate\Pagination\LengthAwarePaginator;

class GamePlayRepository extends BaseRepository implements GamePlayRepositoryInterface
{
    public function model(): string
    {
        return GamePlay::class;
    }

    public function generateGameHistory(int $gameId, int $userId): \Illuminate\Database\Eloquent\Model|null
    {
        $gamePlay = $this->getGamePlay($gameId, $userId);

        if (!$gamePlay) {
            $gamePlay = new GamePlay([
                'game_id' => $gameId,
                'user_id' => $userId,
                'best_score' => 0,
            ]);

            $gamePlay->save();
        }

        $gamePlay->gamePlayHistories()->create([
            'score' => null,
            'played_at' => now(),
            'scene' => 'scene1'
        ])->save();

        return $gamePlay->gamePlayHistories()->latest('id')->first();
    }

    public function removeUnFinishGame(int $gameId, int $userId): void
    {
        $gamePlay = $this->getGamePlay($gameId, $userId);

        if (!$gamePlay) {
            return;
        }

        $gamePlay->gamePlayHistories()
            ->where('score', null)
            ->where('finished_at', null)
            ->where('scene', 'scene1')
            ->delete();
    }

    public function getGamePlay(int $gameId, int $userId): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->getModel()->where('game_id', $gameId)->where('user_id', $userId)->first();
    }

    public function getPaginatedGamePlays(int $perPage): LengthAwarePaginator
    {
        return $this->getModel()->with(['user', 'game', 'gamePlayHistories'])
            ->paginate($perPage);
    }
}
