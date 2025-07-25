<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\CategoriesIncomeRequest;
use App\Services\Cabinet\CategoriesIncomeService;
use App\Models\CategoriesIncome;


class CategoriesIncomeController extends Controller
{
    public function index(CategoriesIncomeService $service)
    {
        $categories = $service->getCategoriesIncome();

        return view('cabinet.profile.categories-income', compact('categories'));
    }

    public function store(CategoriesIncomeRequest $request, CategoriesIncomeService $service)
    {
        $service->store($request);
        return redirect()->back()->with('success', 'Категория добавлена.');
    }

    public function update(CategoriesIncomeRequest $request, CategoriesIncome $categoriesIncome)
    {
        $categoriesIncome->update($request->only(['name']));

        return redirect()->back()->with('success', 'Категория обновлена.');
    }

    public function destroy(CategoriesIncome $categoriesIncome)
    {
        $categoriesIncome->delete();
        return redirect()->back()->with('success', 'Категория удалёна.');
    }
}
