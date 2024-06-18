<?php

namespace App\DAL\Repositories;

use App\DAL\Interfaces\GameRepositoryInterface;
use App\Models\Game;
use Illuminate\Database\Eloquent\Model;

class GameRepository extends BaseRepository implements GameRepositoryInterface
{
    public function model(): string
    {
        return Game::class;
    }

    public function list(int $pageSize, int $page): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->getModel()->paginate($pageSize, ['*'], 'page', $page);
    }

    public function gameDetail(int $gameId): Model
    {
        return $this->getModel()->findOrFail($gameId);
    }
}
