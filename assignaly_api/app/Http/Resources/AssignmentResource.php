<?php

namespace App\Http\Resources;

use App\Models\Assignment;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     *  @mixin Assignment
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'title' => $this->title,
            'description' => $this->description,
            'due_at' => $this->due_at,
            'finished_at' => $this->finished_at,
            'review' => $this->review,
            'status' => $this->status,
            'integration_type' => $this->integration_type,
            'remote_repository' => $this->remote_repository,
            'owner' => UserResource::make($this->owner),
            'users' => UserResource::collection($this->users->sortByDesc('role_id')),
        ];
    }
}
