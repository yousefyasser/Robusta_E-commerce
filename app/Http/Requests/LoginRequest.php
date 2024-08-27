<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $errors,
        ], 422);

        throw new HttpResponseException($response);
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return string|null
     */
    public function authenticate()
    {
        $credentials = $this->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            $response = response()->json([
                'status' => 'error',
                'message' => 'Incorrect email or password',
                'errors' => [
                    'email' => ['Invalid credentials'],
                    'password' => ['Invalid credentials'],
                ],
            ], 401);

            throw new HttpResponseException($response);
        }

        return $token;
    }
}
