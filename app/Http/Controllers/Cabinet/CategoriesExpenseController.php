<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;

use App\Http\Requests\Cabinet\CategoriesExpenseRequest;
use App\Services\Cabinet\CategoriesExpenseService;
use App\Models\CategoriesExpense;


class CategoriesExpenseController extends Controller
{
    public function index(CategoriesExpenseService $service)
    {
        $categories = $service->getCategoriesExpense();
        return view('cabinet.profile.categories-expense', compact('categories'));
    }

    public function store(CategoriesExpenseRequest $request, CategoriesExpenseService $service)
    {
        $service->store($request);
        return redirect()->back()->with('success', 'Категория добавлена.');
    }


    public function update(CategoriesExpenseRequest $request, CategoriesExpense $categoriesExpense)
    {
        $categoriesExpense->update($request->only('name'));
        return redirect()->back()->with('success', 'Категория обновлена.');
    }


    public function destroy(CategoriesExpense $categoriesExpense)
    {
        $categoriesExpense->delete();
        return redirect()->back()->with('success', 'Категория удалёна.');
    }
}
