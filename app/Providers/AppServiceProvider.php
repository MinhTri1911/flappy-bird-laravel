<?php

namespace App\Providers;

use App\DAL\Interfaces\GamePlayHistoryRepositoryInterface;
use App\DAL\Interfaces\GamePlayRepositoryInterface;
use App\DAL\Interfaces\GameRepositoryInterface;
use App\DAL\Repositories\GamePlayHistoryRepository;
use App\DAL\Repositories\GamePlayRepository;
use App\DAL\Repositories\GameRepository;
use App\Models\GamePlay;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GameRepositoryInterface::class, function () {
            return new GameRepository();
        });

        $this->app->bind(GamePlayRepositoryInterface::class, function () {
            return new GamePlayRepository();
        });

        $this->app->bind(GamePlayHistoryRepositoryInterface::class, function () {
            return new GamePlayHistoryRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
