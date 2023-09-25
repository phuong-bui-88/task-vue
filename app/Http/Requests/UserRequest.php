<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Validator;

class UserRequest extends FormRequest
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
        $defaultRules = [
            'name' => 'required',
            'password' => 'required|min:3',
        ];

        if ($this->routeIs('users.store')) {
            $defaultRules['email'] = 'required|email';
            $defaultRules['password'] = 'required|confirmed|min:3';
        }

        if ($this->routeIs('forgot.password')) {
            $defaultRules = [];
            $defaultRules['email'] = 'required|email';
        }

        if ($this->routeIs('reset.password')) {
            $defaultRules = [];
            $defaultRules['token'] = 'required';
            $defaultRules['password'] = 'required|confirmed|min:3';
        }

        return $defaultRules;
    }

    /**
     * @throws \HttpResponseException
     */
    public function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator) {
        throw new HttpResponseException(response()->json(array_merge([
            'success' => false,
            'message' => 'Validation errors',
        ], $validator->errors()->toArray()), Response::HTTP_FORBIDDEN));
    }
}
