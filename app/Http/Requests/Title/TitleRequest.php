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
            'status' => 'required|in:' . implode(',', array_map(function ($status) {
                return $status->value;
            }, TitleStatus::cases())),
        ];
    }

    /**
     * Custom messages for validation rules.
     */
    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'abstract.required' => 'The abstract field is required.',
            'proposal_file.mimes' => 'The proposal file must be a PDF, DOCX, or DOC file.',
            'supervisor_id.required' => 'The supervisor field is required.',
            'supervisor_id.exists' => 'The selected supervisor is invalid.',
            'status.required' => 'The status field is required.',
            'status.in' => 'The selected status is invalid.',
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
