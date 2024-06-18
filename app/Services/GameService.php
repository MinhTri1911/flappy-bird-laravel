<?php

namespace App\Services;

use App\DAL\Interfaces\GameRepositoryInterface;
use App\Utils\RewardEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GameService
{
    public function __construct(private GameRepositoryInterface $repository)
    {
    }

    public function list(int $pageSize = 10, int $page = 1): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repository->list($pageSize, $page);
    }

    public function gamePlay(int $gameId): array
    {
        $game = $this->repository->gameDetail($gameId);

        return [
            'view_name' => Str::slug($game->name),
            'setting' => $game->setting,
            'rewards' => [
                RewardEnum::Iphone->name => RewardEnum::Iphone->value,
                RewardEnum::Voucher->name => RewardEnum::Voucher->value,
            ],
        ];
    }
}
