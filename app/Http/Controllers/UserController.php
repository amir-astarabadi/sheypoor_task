<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function store(UserCreateRequest $request)
    {
        $user = User::generate($request->only(User::FIELDS));

        return UserResource::make($user);
    }
}
