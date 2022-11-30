<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(User::all());
    }

    public function store(UserStoreRequest $request): UserResource
    {
        Gate::authorize('can_create', User::class);

        return UserResource::make(User::create($request->validated()));
    }

    public function show(User $user): UserResource
    {
        Gate::authorize('can_view', $user);

        return UserResource::make($user);
    }

    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        Gate::authorize('can_update', $user);

        $user->update($request->validated());

        return UserResource::make($user);
    }

    public function destroy(User $user): bool
    {
        Gate::authorize('can_delete', $user);

        return $user->deleteOrFail();
    }
}
