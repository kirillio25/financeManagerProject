<?php

namespace App\Services\Cabinet;

use App\Models\CategoriesIncome;
use App\Http\Requests\Cabinet\CategoriesIncomeRequest;

class CategoriesIncomeService
{
    public function getCategoriesIncome()
    {
        return CategoriesIncome::where('user_id', auth()->id())->get();
    }

    public function store(CategoriesIncomeRequest $request): void
    {
        CategoriesIncome::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
        ]);
    }
}
