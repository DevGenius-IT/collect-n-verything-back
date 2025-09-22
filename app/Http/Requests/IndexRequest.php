<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => 'integer|min:1',
            'limit' => 'integer|min:1|max:100',
            'fields' => 'sometimes|string'
        ];
    }
    public function selectedFields(): ?array
    {
        $header = $this->header('fields');

        if ($header) {
            return array_map('trim', explode(',', $header));
        }

        return null;
    }
}
