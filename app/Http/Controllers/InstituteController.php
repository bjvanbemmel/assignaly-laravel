<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstituteStoreRequest;
use App\Http\Requests\InstituteUpdateRequest;
use App\Models\Institute;

class InstituteController extends Controller
{
    public function index(): \Illuminate\Support\Collection
    {
        return Institute::all();
    }

    public function store(InstituteStoreRequest $request): \App\Models\Institute
    {
        return Institute::create($request->validated());
    }

    public function show(Institute $institute): \App\Models\Institute
    {
        return $institute;
    }

    public function update(InstituteUpdateRequest $request, Institute $institute): \App\Models\Institute
    {
        $institute->update($request->all());

        return $institute;
    }

    public function destroy(Institute $institute): bool
    {
        return $institute->deleteOrFail();
    }
}
