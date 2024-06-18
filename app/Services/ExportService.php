<?php

namespace App\Services;

use App\DAL\Interfaces\GamePlayRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class ExportService
{
    public function __construct(private GamePlayRepositoryInterface $repository)
    {

    }

    public function exportData(int $perPage = 15): LengthAwarePaginator
    {
        // Fetch the paginated game play data including user info and history
        $gamePlays = $this->repository->getPaginatedGamePlays($perPage);

        // Format the data
        $formattedData = $gamePlays->map(function ($gamePlay) {
            return [
                'user_id' => $gamePlay->user_id,
                'user_name' => $gamePlay->user->name,
                'user_email' => $gamePlay->user->email,
                'user_phone' => $gamePlay->user->phone,
                'game_name' => $gamePlay->game->name,
                'best_score' => $gamePlay->best_score,
                'histories' => $gamePlay->gamePlayHistories->map(function ($history) {
                    return [
                        'score' => $history->score,
                        'played_at' => $history->played_at,
                        'finished_at' => $history->finished_at,
                        'reward_issued_at' => $history->reward_issued_at,
                    ];
                }),
            ];
        });

        // Return paginated formatted data
        return new LengthAwarePaginator(
            $formattedData,
            $gamePlays->total(),
            $gamePlays->perPage(),
            $gamePlays->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
