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

    public function show(int $id): \App\Models\Institute
    {
        return Institute::findOrFail($id);
    }

    public function update(Request $request, int $id)
    {
        $updatedInstitute = Institute::find($id);
        $updatedInstitute->update($request->all());

        return $updatedInstitute;
    }

    public function destroy(int $id): bool
    {
        return Institute::findOrFail($id)->delete();
    }
}
