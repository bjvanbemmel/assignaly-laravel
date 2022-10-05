<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentStoreRequest;
use App\Http\Requests\AssignmentUpdateRequest;
use App\Models\Assignment;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): \Illuminate\Support\Collection
    {
        return Assignment::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssignmentStoreRequest $request): Assignment
    {
        return Assignment::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Assignment $assignment): Assignment
    {
        return $assignment;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Assignment $assignment, AssignmentUpdateRequest $request): Assignment
    {
        $assignment->update($request->validated());

        return $assignment;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assignment $assignment): bool
    {
        return $assignment->deleteOrFail();
    }
}
