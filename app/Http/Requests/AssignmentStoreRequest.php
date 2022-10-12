<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'owner_id' => 'int|required|exists:users,id',
            'users' => 'array|required',
            'users.*' => 'int|exists:users,id',
            'title' => 'string|required|min:1|max:255',
            'description' => 'string|max:10000',
            'due_at' => 'date',
            'finished_at' => 'date',
            'numeric_review' => 'int|min:1',
            'feedback' => 'string'
        ];
    }
}
