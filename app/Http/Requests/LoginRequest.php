<?php

namespace App\Http\Requests;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\ValidationRuleException;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            'email' => 'required|bail|email|max:255',
            'password' => 'required|bail|min:8',
        ];
    }

    public function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->after(function (Validator $validator) {
            if ($validator->errors()->count()) {
                return;
            }

            $params = request(['email', 'password']);
            $user = User::query()->where('email', $params['email'])->first();

            if (!$user->verified) {
                throw new ValidationRuleException('email','not_verified');
            }
            if (!Hash::check($params['password'], $user->password)) {
                throw new InvalidCredentialsException();
            }

            $this->merge(['credentials' => $params, 'user' => $user]);
        });
        return $validator;
    }
}
