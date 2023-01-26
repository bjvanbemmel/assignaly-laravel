<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentStoreRequest;
use App\Http\Requests\AssignmentUpdateRequest;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $assignments = Auth::user()->allAssignments()
            ->orderByDesc('assignments.' . $request->input('orderBy') ?? 'created_at')
            ->paginate(perPage: $request->input('perPage') ?? 30);

        return AssignmentResource::collection($assignments);
    }

    public function latest(): AnonymousResourceCollection
    {
        $assignments = Auth::user()->allAssignments()
            ->orderByDesc('assignments.created_at')
            ->take(5)
            ->get();

        return AssignmentResource::collection($assignments);
    }

    public function store(AssignmentStoreRequest $request): AssignmentResource | JsonResponse
    {
        if (Auth::user()->role->level < 2) {
            return new JsonResponse(['error' => 'You\'re not authorized to create an assignment.'], 401);
        }

        $validated = collect($request->validated())->except('users')->toArray();
        $users = $request->validated()['users'];

        $assignment = Assignment::create($validated);
        $assignment->users()->sync($users);

        return AssignmentResource::make($assignment);
    }

    public function show(Assignment $assignment): AssignmentResource | JsonResponse
    {
        if ((!$assignment->users->contains(Auth::user()) && Auth::user()->id !== $assignment->owner->id) && Auth::user()->role->level < 2) {
            return new JsonResponse(['error' => 'You\'re not authorized to view this assignment'], 401);
        }

        return AssignmentResource::make($assignment);
    }

    public function update(Assignment $assignment, AssignmentUpdateRequest $request): AssignmentResource | JsonResponse
    {
        if (Auth::user()->role->level < 2) {
            return new JsonResponse(['error' => 'You\'re not authorized to update an assignment.'], 401);
        }

        $validated = collect($request->validated())->except('users')->toArray();

        $assignment->update($validated);

        if (isset($request->validated()['users'])) {
            $users = $request->validated()['users'];
            $assignment->users()->sync($users);
        }

        return AssignmentResource::make($assignment);
    }

    public function destroy(Assignment $assignment): bool | JsonResponse
    {
        if (Auth::user()->role->level < 2) {
            return new JsonResponse(['error' => 'You\'re not authorized to delete an assignment.'], 401);
        }

        return $assignment->deleteOrFail();
    }
}
