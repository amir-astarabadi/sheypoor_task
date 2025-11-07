<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'top_n' => ['required', 'integer', 'min:1'],
            'offset' => ['required', 'integer', 'min:0'],
        ];
    }

    public function prepareForValidation():void
    {
        $this->merge([
            'top_n' => $this->get('top_n', 10),
            'offset' => $this->get('offset', 0),
        ]);
    }
}
