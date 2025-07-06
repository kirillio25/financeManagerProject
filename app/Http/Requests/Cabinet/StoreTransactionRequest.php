<?php 

namespace App\Http\Requests\Cabinet;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric',
            'type_id' => 'required|in:0,1',
            'category_id' => 'required|integer',
            'account_id' => 'required|integer',
            'date' => 'required|date|before_or_equal:today',
        ];
    }
}
