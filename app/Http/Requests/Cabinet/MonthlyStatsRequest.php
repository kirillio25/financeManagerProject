<?php

namespace App\Http\Requests\Cabinet;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class MonthlyStatsRequest extends FormRequest
{
    // Разрешить выполнение запроса для всех авторизованных пользователей
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'month' => ['nullable', 'date_format:Y-m'],
        ];
    }

    public function getMonth(): Carbon
    {
        return Carbon::createFromFormat('Y-m', $this->input('month', now()->format('Y-m')));
    }
}
