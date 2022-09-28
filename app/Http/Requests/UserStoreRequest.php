<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
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
            'name' => 'min:2|max:255|required',
            'email' => 'email|required|unique:users,email',
            'password' => Password::default()->required(),
            'institute' => 'required|exists:institutes,id',
            'role' => 'required|exists:roles,id',
        ];
    }
}
