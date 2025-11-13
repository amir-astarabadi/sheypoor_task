<?php

namespace Tests\Feature\UserController;

use App\Jobs\UpdateUserScoreJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Http\Response;
use App\Models\User;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{

    public function test_user_update_score_happy_path(): void
    {
        // setup
        Queue::fake();
        $user = User::factory()->create();
        $data = ['score' => random_int(1, 100)];

        // act
        $response = $this->putJson(route('users.update', ['user' => $user]), $data);

        // assert
        $response->assertStatus(Response::HTTP_OK);
        $this->assertSame($response->json('data.score'), $data['score']);
        Queue::assertPushed(UpdateUserScoreJob::class);        
    }
}
