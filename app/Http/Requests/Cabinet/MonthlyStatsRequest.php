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

    // Правила валидации: параметр "month" должен быть в формате "Y-m"
    public function rules(): array
    {
        return [
            'month' => ['nullable', 'date_format:Y-m'],
        ];
    }

    // Метод для получения Carbon-объекта месяца — нормализует входные данные
    public function getMonth(): Carbon
    {
        return Carbon::createFromFormat('Y-m', $this->input('month', now()->format('Y-m')));
    }
}
