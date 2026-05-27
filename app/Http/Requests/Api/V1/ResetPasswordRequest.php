<?php

namespace App\Http\Requests\Api\V1;

use App\Services\Auth\AuthLoginConfiguration;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('code') && ! is_string($this->input('code'))) {
            $this->merge([
                'code' => (string) $this->input('code'),
            ]);
        }
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        $authLogin = app(AuthLoginConfiguration::class);

        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'code' => [
                'required',
                'string',
                'regex:/^[0-9]+$/',
                'size:'.$authLogin->otpCodeLength(),
            ],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];
    }
}
