<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GamePlayService;
use App\Utils\Response;
use App\Utils\RewardEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GamePlayController extends Controller
{
    public function __construct(private readonly GamePlayService $service)
    {

    }

    public function start(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'gameId' => 'required|numeric|exists:games,id',
        ]);

        if ($validator->fails()) {
            return response()->json(Response::error(message: 'Fail to finish game', errors: $validator->errors()->toArray()));
        }

        try {
            $data = $this->service->startGamePlay($request->get('gameId'), $request->user()->id);

            return response()->json(Response::success('Start game successfully', [
                'history_id' => $data['history_id'],
                'game_play_id' => $data['game_play_id']
            ]));
        } catch (\Exception $exception) {
            logger()->getLogger()->error($exception->getMessage());

            return response()->json(Response::error(message: $exception->getMessage()));
        }
    }

    public function finish(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'gameId' => 'required|numeric|exists:games,id',
            'gamePlayId' => 'required|numeric|exists:game_plays,id',
            'historyId' => 'required|numeric|exists:game_play_histories,id',
            'reward' => 'nullable|in:' . implode(',', [RewardEnum::Voucher->name, RewardEnum::Iphone->name]),
            'score' => 'required|numeric|min:0',
            'scene' => 'required|string|in:' . implode(',', ['scene1', 'scene2', 'scene3'])
        ]);

        if ($validator->fails()) {
            return response()->json(Response::error(message: 'Fail to finish game', errors: $validator->errors()->toArray()));
        }

        try {
            $this->service->finishGamePlay(
                gameId: $request->get('gameId'),
                gamePlayId: $request->get('gamePlayId'),
                userId: $request->user()->id,
                historyId: $request->get('historyId'),
                score: $request->get('score'),
                scene: $request->get('scene'),
                reward: $request->get('reward')
            );
            return response()->json(Response::success('Finish game successfully'));
        } catch (\Exception $exception) {
            logger()->getLogger()->error($exception->getMessage());

            return response()->json(Response::error(message: $exception->getMessage()));
        }
    }
}
