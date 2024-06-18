<?php

namespace App\DAL\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface GamePlayRepositoryInterface extends BaseRepositoryInterface
{
    public function getGamePlay(int $gameId, int $userId): \Illuminate\Database\Eloquent\Model|null;

    public function generateGameHistory(int $gameId, int $userId): \Illuminate\Database\Eloquent\Model|null;

    public function removeUnFinishGame(int $gameId, int $userId): void;

    public function getPaginatedGamePlays(int $perPage): LengthAwarePaginator;
}
