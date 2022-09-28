<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): \Illuminate\Support\Collection
    {
        return User::all();
    }

    public function store(UserStoreRequest $request)
    {
        $newUser = User::create($request->validated());

        return $newUser;
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        return $user;
    }

    public function destroy(User $user)
    {
        return $user->deleteOrFail();
    }
}
