<?php

namespace App\Services\Cabinet;

use App\Models\CategoriesIncome;

class CategoriesIncomeService
{
    public function getCategoriesIncome()
    {
        return CategoriesIncome::where('user_id', auth()->id())->get();
    }
}
