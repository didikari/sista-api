<?php

namespace App\Http\Requests\Grade;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GradeRequest extends FormRequest
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
            'a1' => 'required|numeric|between:0,100',
            'a2' => 'required|numeric|between:0,100',
            'a3' => 'required|numeric|between:0,100',
            'a4' => 'required|numeric|between:0,100',
            'a5' => 'required|numeric|between:0,100',
            'a6' => 'required|numeric|between:0,100',
            'status' => 'required|in:pending,finalized',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
