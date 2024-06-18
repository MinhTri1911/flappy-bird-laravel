<?php

namespace App\DAL\Repositories;

use App\DAL\Interfaces\GamePlayHistoryRepositoryInterface;
use App\Models\GamePlayHistory;

class GamePlayHistoryRepository extends BaseRepository implements GamePlayHistoryRepositoryInterface
{

    public function model(): string
    {
        return GamePlayHistory::class;
    }

    public function updateHistory(int $historyId, array $data): bool
    {
        return $this->getModel()->findOrFail($historyId)->update($data);
    }
}
