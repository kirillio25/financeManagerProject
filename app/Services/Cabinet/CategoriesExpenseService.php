<?php

namespace App\Services\Cabinet;

use App\Models\CategoriesExpense;
use App\Http\Requests\Cabinet\CategoriesExpenseRequest;

class CategoriesExpenseService
{
    public function getCategoriesExpense()
    {
        return CategoriesExpense::where('user_id', auth()->id())->get();
    }
    public function store(CategoriesExpenseRequest $request): void
    {
        CategoriesExpense::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
        ]);
    }
}
