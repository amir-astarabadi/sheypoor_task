<?php

namespace App\Providers;

use App\Services\LeaderBoard\LeaderBoardService;
use App\Services\LeaderBoard\RedisLeaderBoard;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(LeaderBoardService::class, RedisLeaderBoard::class);
    }
}
