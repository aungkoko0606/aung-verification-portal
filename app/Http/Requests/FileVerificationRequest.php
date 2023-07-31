<?php
declare(strict_types=1);
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileVerificationRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'verification_file' => ['required', 'file', 'mimes:json']
        ];
    }

    public function messages(): array
    {
        return [
            'verification_file.required' => 'The verification_file field is required.',
            'verification_file.file' => 'The verification_file type should be file.',
            'verification_file.mimes' => 'The verification_file should be json file.',
        ];
    }
}
