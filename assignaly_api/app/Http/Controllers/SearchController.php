<?php

namespace App\Http\Controllers;

use App\Http\Resources\AssignmentResource;
use App\Http\Resources\UserResource;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search (Request $request)
    {
        if (strlen($request->input('query')) === 0) {
            return new JsonResponse([
                'users' => [],
                'assignments' => [],
            ]);
        } else {
            return new JsonResponse([
                'users' => UserResource::collection(User::query()->where('name', 'LIKE', "%{$request->input('query')}%")->limit(5)->get()),
                'assignments' => AssignmentResource::collection(Auth()->user()->allAssignments()->where('title', 'LIKE', "%{$request->input('query')}%")->limit(5)->get()),
            ]);
        }
    }
}
