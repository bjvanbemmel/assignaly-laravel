<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index(): \Illuminate\Support\Collection
    {
        Gate::authorize('can_index', User::class);

        return User::all();
    }

    public function store(UserStoreRequest $request): User
    {
        Gate::authorize('can_create', User::class);

        return User::create($request->validated());
    }

    public function show(User $user): User
    {
        Gate::authorize('can_view', $user);

        return $user;
    }

    public function update(UserUpdateRequest $request, User $user): User
    {
        Gate::authorize('can_update', $user);

        $user->update($request->validated());

        return $user;
    }

    public function destroy(User $user): bool
    {
        Gate::authorize('can_delete', $user);

        return $user->deleteOrFail();
    }
}
