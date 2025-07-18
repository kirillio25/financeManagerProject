<?php

namespace App\Services\Cabinet;

use App\Models\CategoriesExpense;

class CategoriesExpenseService
{
    public function getCategoriesExpense()
    {
        return CategoriesExpense::where('user_id', auth()->id())->get();
    }
}
