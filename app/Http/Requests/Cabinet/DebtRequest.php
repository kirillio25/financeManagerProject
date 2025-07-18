<?php

namespace App\Http\Requests\Cabinet;

use Illuminate\Foundation\Http\FormRequest;

class DebtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'debt_direction' => 'required|integer|in:0,1', // 0 — я должен, 1 — мне должны
            'contact_method' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'integer|in:0,1', // 1 — активный, 0 — закрыт
            'amount' => 'required|numeric|min:0.01',
        ];
    }


}
