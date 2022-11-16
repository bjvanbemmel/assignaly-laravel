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
            'owner' => $this->owner,
            'users' => $this->users,
        ];
    }
}
