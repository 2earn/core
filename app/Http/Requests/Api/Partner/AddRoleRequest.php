<?php

namespace App\Http\Requests\Api\Partner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class AddRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Adjust authorization logic as needed
        // You might want to check if the authenticated user has permission to assign roles
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
            'platform_id' => 'required|integer|exists:platforms,id',
            'user_id' => 'required|integer|exists:users,id',
            'role' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'platform_id.required' => 'Platform ID is required',
            'platform_id.integer' => 'Platform ID must be an integer',
            'platform_id.exists' => 'The specified platform does not exist',
            'user_id.required' => 'User ID is required',
            'user_id.integer' => 'User ID must be an integer',
            'user_id.exists' => 'The specified user does not exist',
            'role.required' => 'Role is required',
            'role.string' => 'Role must be a string',
            'role.max' => 'Role name must not exceed 255 characters',
            'role.exists' => 'The specified role does not exist',
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
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}

