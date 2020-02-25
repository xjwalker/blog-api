<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|bail|min:3|unique:users,name|max:255',
            'email' => 'required|bail|email|unique:users,email|max:255',
            'password' => 'required|bail|min:8|max:128',
        ];
    }
}
