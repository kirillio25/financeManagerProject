<?php 

namespace App\DTOs\Cabinet;

use Carbon\Carbon;

class MonthlyStatsDTO
{
    // DTO хранит нормализованные параметры периода
    public function __construct(
        public Carbon $start,
        public Carbon $end,
        public string $selectedMonth,
        public string $prevMonth,
        public string $nextMonth,
    ) {}

    // Упрощённый способ создать DTO из Carbon-месяца
    public static function fromCarbonMonth(Carbon $carbonMonth): self
    {
        $start = $carbonMonth->copy()->startOfMonth();
        $end = $carbonMonth->copy()->endOfMonth();

        return new self(
            selectedMonth: $carbonMonth->format('Y-m'),
            prevMonth: $carbonMonth->copy()->subMonth()->format('Y-m'),
            nextMonth: $carbonMonth->copy()->addMonth()->format('Y-m'),
            start: $start,
            end: $end,
        );
    }
}
