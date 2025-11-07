<?php

namespace App\Http\Controllers;

use App\Services\LeaderBoard\LeaderBoardService;
use App\Http\Resources\UserResourceCollection;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Jobs\UpdateUserScoreJob;
use App\Models\User;

class UserController extends Controller
{
    public function index(UserIndexRequest $request, LeaderBoardService $leaderBoardService)
    {
        $topN = $leaderBoardService->getTopN($request->validated('top_n'), $request->validated('offset'));
        
        $users = User::whereIn('name', array_keys($topN))->get()->sortBy('rank');

        return UserResourceCollection::make($users);
    }

    public function show(User $user)
    {
        return UserResource::make($user);
    }

    
    public function store(UserCreateRequest $request)
    {
        $user = User::generate($request->only(User::FIELDS));

        return UserResource::make($user);
    }

    public function update(User $user, UserUpdateRequest $request, LeaderBoardService $leaderBoardService)
    {
        $leaderBoardService->update($user->getLeaderBoardKey(), $request->get('score'));

        UpdateUserScoreJob::dispatch($user->id);
        
        return UserResource::make($user);
    }
}

