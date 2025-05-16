<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'name'     => ['required', 'max:100'],
            'username' => ['required', 'unique:users', 'max:50','alpha_num', 'alpha_dash'],
            'password' => ['required', 'max:100', 'min:8'],
            'email'    => ['required', 'unique:users', 'max:100', 'email'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->isApiRequest()) {
            throw new HttpResponseException(
                response()->json([
                    "message" => "Validation errors",
                    "errors" => $validator->errors()
                ], 400)
            );
        }

        parent::failedValidation($validator);
    }

    protected function isApiRequest(): bool
    {
        // Cek apakah route saat ini adalah route API
        return Route::is('api.*') ||
            str_starts_with($this->path(), 'api/') ||
            $this->wantsJson();
    }
}
