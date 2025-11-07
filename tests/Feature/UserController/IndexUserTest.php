<?php

namespace Tests\Feature\UserController;

use App\Services\LeaderBoard\LeaderBoardService;
use Illuminate\Http\Response;
use App\Models\User;
use Tests\TestCase;

class IndexUserTest extends TestCase
{

    public function test_top_n_users_happy_path(): void
    {
        // setup
        $users = User::factory($count = 3)->create();
        $leaderBoardService = resolve(LeaderBoardService::class);
        $users->each(fn($user) => $leaderBoardService->update($user->getLeaderBoardKey(), $user->getRawOriginal('score')));

        // act
        $response = $this->getJson(route('users.index'));

        // assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount($count, 'data');
    }

    public function test_top_n_users_with_offset(): void
    {
        // setup
        $users = User::factory($count = 2)->create()->sortBy('rank');
        $leaderBoardService = resolve(LeaderBoardService::class);
        $users->each(fn($user) => $leaderBoardService->update($user->getLeaderBoardKey(), $user->getRawOriginal('score')));

        // act
        $response = $this->getJson(route('users.index') . '?' . http_build_query(['offset' =>  $count  / 2]));

        // assert
        $response->assertJsonCount($count  / 2, 'data');
        $this->assertSame($response->json('data.0.rank'), 2);
    }


    public function test_top_n_users_with_top_n(): void
    {
        // setup
        $users = User::factory($count = 2)->create()->sortBy('rank');
        $leaderBoardService = resolve(LeaderBoardService::class);
        $users->each(fn($user) => $leaderBoardService->update($user->getLeaderBoardKey(), $user->getRawOriginal('score')));

        // act
        $response = $this->getJson(route('users.index') . '?' . http_build_query(['top_n' =>  1]));

        // assert
        $response->assertJsonCount(1, 'data');
        $this->assertSame($response->json('data.0.rank'), 1);
    }
}
