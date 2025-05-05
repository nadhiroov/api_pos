<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
// use Illuminate\Http\Request;


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
            'username' => ['required', 'max:50', 'min:3'],
            'password' => ['required', 'max:100'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->isApiRequest()) {
            throw new HttpResponseException(
                response()->json([
                    "message" => "Validation errors",
                    "errors" => $validator->errors()
                ],400)
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
