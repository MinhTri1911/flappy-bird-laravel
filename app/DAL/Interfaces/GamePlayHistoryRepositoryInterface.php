<?php

namespace App\DAL\Interfaces;

interface GamePlayHistoryRepositoryInterface extends BaseRepositoryInterface
{
    public function updateHistory(int $historyId, array $data): bool;
}
