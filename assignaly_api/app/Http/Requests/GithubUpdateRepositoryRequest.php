<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GithubUpdateRepositoryRequest extends FormRequest
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
            'name' => 'string|required|min:1|max:100|alpha_dash',
            'description' => 'string|max:255|nullable',
            'private' => 'boolean|required',
        ];
    }
}
