<?php

namespace App\DAL\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface GameRepositoryInterface extends BaseRepositoryInterface
{
    public function list(int $pageSize, int $page): \Illuminate\Pagination\LengthAwarePaginator;

    public function gameDetail(int $gameId): Model;
}
