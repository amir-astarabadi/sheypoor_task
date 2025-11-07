<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\LeaderBoard\LeaderBoardService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $leaderBoardService = resolve(LeaderBoardService::class);

        User::factory(50)
            ->create()
            ->each(function ($user) use ($leaderBoardService) {
                $leaderBoardService->update($user->getLeaderBoardKey(), $user->getRawOriginal('score'));
            });
    }
}
