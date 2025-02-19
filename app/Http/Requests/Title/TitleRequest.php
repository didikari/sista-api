<?php

namespace App\Http\Requests\Title;

use App\Enums\TitleStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TitleRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'proposal_file' => 'nullable|file|mimes:pdf,docx,doc|max:10240',
            'supervisor_id' => 'required|exists:lecturers,id',
            'status' => 'sometimes|in:' . implode(',', array_map(function ($status) {
                return $status->value;
            }, TitleStatus::cases())),
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
