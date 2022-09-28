<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(): \Illuminate\Support\Collection
    {
        return Role::all();
    }

    public function store(RoleStoreRequest $request)
    {
        return Role::create($request->validated());
    }

    public function show(Role $role)
    {
        return $role;
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        return $role->update($request->all());
    }

    public function destroy(Role $role)
    {
        return $role->deleteOrFail();
    }
}
