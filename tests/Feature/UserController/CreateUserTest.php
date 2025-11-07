<?php

namespace Tests\Feature\UserController;

use Illuminate\Http\Response;
use App\Models\User;
use Tests\TestCase;

class CreateUserTest extends TestCase
{

    public function test_user_creation_happy_path(): void
    {
        // setup
        $data = User::factory()->make()->toArray();
        $data['password'] = 'password';
        $data['password_confirmation'] = 'password';

        // act
        $response = $this->postJson(route('users.store.store'), $data);

        // assert
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_user_email_is_unique(): void
    {
        // setup
        $oldUser = User::factory()->create();

        $data = User::factory()->make()->toArray();
        $data['password'] = 'password';
        $data['password_confirmation'] = 'password';
        $data['email'] = $oldUser->email;

        // act
        $response = $this->postJson(route('users.store.store'), $data);

        // assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertSame('The email has already been taken.', $response->json('message'));
    }

    public function test_user_name_is_unique(): void
    {
        // setup
        $oldUser = User::factory()->create();

        $data = User::factory()->make()->toArray();
        $data['password'] = 'password';
        $data['password_confirmation'] = 'password';
        $data['name'] = $oldUser->name;

        // act
        $response = $this->postJson(route('users.store.store'), $data);

        // assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertSame('The name has already been taken.', $response->json('message'));
    }
}
