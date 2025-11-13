<?php

namespace Tests\Feature\UserController;

use App\Services\LeaderBoard\LeaderBoardService;
use Illuminate\Http\Response;
use App\Models\User;
use Tests\TestCase;

class UserShowTest extends TestCase
{

    public function test_show_user_happy_path(): void
    {
        // setup
        $user = User::factory()->create();
        resolve(LeaderBoardService::class)->update($user->getLeaderBoardKey(), $user->getRawOriginal('score'));

        // act
        $response = $this->getJson(route('users.show' , ['user' => $user]));

        // assert
        $response->assertStatus(Response::HTTP_OK);
        $this->assertSame($response->json('data.score'), $user->getRawOriginal('score'));
    }
}
