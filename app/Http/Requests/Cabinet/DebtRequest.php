<?php

namespace App\Http\Requests\Cabinet;

use Illuminate\Foundation\Http\FormRequest;

class TransactionHaistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date_format:Y-m-d\TH:i',
        ];
    }

}
