<?php

namespace App\Http\Controllers;

use App\Services\GameService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\View\View;
use Illuminate\Support\Facades\View as ViewHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GameController extends Controller
{
    public function __construct(private readonly GameService $service)
    {

    }

    public function index(): View
    {
        return view('game-list', [
            'games' => $this->service->list(),
        ]);
    }

    public function show(int $id): View
    {
        try {
            $gamePlay = $this->service->gamePlay($id);

            if (!ViewHelper::exists($gamePlay['view_name'])) {
                throw new NotFoundHttpException(message: 'This game does not support yet', code: 404);
            }

            return view($gamePlay['view_name'], [
                'setting' => $gamePlay['setting'],
                'rewards' => $gamePlay['rewards']
            ]);
        } catch (\Exception $exception) {
            logger()->getLogger()->error($exception->getMessage());

            if ($exception instanceof ModelNotFoundException) {
                return view('errors.404', ['exception' => $exception, 'statusCode' => $exception->getCode()], $exception->getCode());
            }

            return view($exception->getCode(), ['exception' => $exception, 'statusCode' => $exception->getCode()], $exception->getCode());
        }
    }
}
