<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentStoreRequest;
use App\Http\Requests\AssignmentUpdateRequest;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AssignmentController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $assignments = Assignment::query()->paginate();

        return AssignmentResource::collection($assignments);
    }

    public function store(AssignmentStoreRequest $request): AssignmentResource
    {
        $validated = collect($request->validated())->except('users')->toArray();
        $users = $request->validated()['users'];

        $assignment = Assignment::create($validated);
        $assignment->users()->sync($users);

        return AssignmentResource::make($assignment);
    }

    public function show(Assignment $assignment): AssignmentResource
    {
        return AssignmentResource::make($assignment);
    }

    public function update(Assignment $assignment, AssignmentUpdateRequest $request): AssignmentResource
    {
        $validated = collect($request->validated())->except('users')->toArray();

        $assignment->update($validated);

        if (isset($request->validated()['users'])) {
            $users = $request->validated()['users'];
            $assignment->users()->sync($users);
        }

        return AssignmentResource::make($assignment);
    }

    public function destroy(Assignment $assignment): bool
    {
        return $assignment->deleteOrFail();
    }
}
