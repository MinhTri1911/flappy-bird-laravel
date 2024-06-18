<?php

namespace App\Services;

use App\DAL\Interfaces\GamePlayHistoryRepositoryInterface;
use App\DAL\Interfaces\GamePlayRepositoryInterface;
use App\Utils\RewardEnum;

class GamePlayService
{
    public function __construct(
        private readonly GamePlayRepositoryInterface $gamePlayRepository,
        private readonly GamePlayHistoryRepositoryInterface $gamePlayHistoryRepository,
    ) {

    }

    /**
     * @throws \Exception
     */
    public function startGamePlay(int $gameId, int $userId): array
    {
        $this->gamePlayRepository->startTransaction();

        $this->gamePlayRepository->removeUnFinishGame($gameId, $userId);
        $history = $this->gamePlayRepository->generateGameHistory($gameId, $userId);
        $gamePlay = $this->gamePlayRepository->getGamePlay($gameId, $userId);

        if (!$history) {
            $this->gamePlayRepository->endTransaction(false);

            throw new \Exception('Can not create game history');
        }

        $this->gamePlayRepository->endTransaction();

        return [
            'history_id' => $history->id,
            'game_play_id' => $gamePlay->id,
        ];
    }

    /**
     * @throws \Exception
     */
    public function finishGamePlay(int $gameId, int $gamePlayId, int $userId, int $historyId, int $score, string $scene, ?string $reward = null): void
    {
        $gamePlay = $this->gamePlayRepository->getGamePlay($gameId, $userId);

        if ($gamePlay->id !== $gamePlayId) {
            throw new \Exception('Game play does not match');
        }

        $this->gamePlayRepository->startTransaction();

        try {
            $bestScore = $gamePlay->gamePlayHistories()->max('score');
            $bestScore = max($score, $bestScore);
            $data = [
                'score' => $score,
                'finished_at' => now(),
                'updated_at' => now(),
                'scene' => $scene,
            ];
            $gamePlay->best_score = $bestScore;

            if (!in_array($gamePlay->reward_type, [RewardEnum::Iphone->name, RewardEnum::Voucher->name]) && $reward) {
                $gamePlay->reward_type = $reward;
                $data['reward_issued_at'] = now();
            }

            $this->gamePlayHistoryRepository->updateHistory($historyId, $data);
            $gamePlay->save();

            $this->gamePlayRepository->endTransaction();
        } catch (\Exception $e) {
            $this->gamePlayRepository->endTransaction(false);

            throw new \Exception('Fail to finish game play');
        }
    }
}
