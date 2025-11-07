<?php

namespace App\Http\Controllers;

use App\Services\LeaderBoard\LeaderBoardService;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Jobs\UpdateUserScoreJob;
use App\Models\User;

class UserController extends Controller
{
    public function store(UserCreateRequest $request)
    {
        $user = User::generate($request->only(User::FIELDS));

        return UserResource::make($user);
    }

    public function update(User $user, UserUpdateRequest $request, LeaderBoardService $leaderBoardService)
    {
        $leaderBoardService->update($user->getLeaderBoardKey(), $request->get('score'));

        UpdateUserScoreJob::dispatch($user->id, $request->get('score'));
        
        return UserResource::make($user);
    }
}

