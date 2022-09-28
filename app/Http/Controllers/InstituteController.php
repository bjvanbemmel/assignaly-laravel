<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstituteStoreRequest;
use App\Models\Institute;
use Illuminate\Http\Request;

class InstituteController extends Controller
{
    public function index(): \Illuminate\Support\Collection
    {
        return Institute::all();
    }

    public function store(InstituteStoreRequest $request): \App\Models\Institute
    {
        $newInstitute = Institute::create($request->validated());

        return $newInstitute;
    }

    public function show(Institute $institute): \App\Models\Institute
    {
        return $institute;
    }

    public function update(Request $request, Institute $institute)
    {
        $institute->update($request->all());

        return $institute;
    }

    public function destroy(Institute $institute): bool
    {
        return $institute->deleteOrFail();
    }
}
