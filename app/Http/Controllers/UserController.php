<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index(): \Illuminate\Support\Collection
    {
        return User::all();
    }

    public function store(UserStoreRequest $request)
    {
        return User::create($request->validated());
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        return $user;
    }

    public function destroy(User $user)
    {
        return $user->deleteOrFail();
    }
}
