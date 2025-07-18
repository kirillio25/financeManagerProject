<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;

use App\Services\Cabinet\CategoriesExpenseService;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\CategoriesExpense;


class CategoriesExpenseController extends Controller
{
    public function index(CategoriesExpenseService $service)
    {
        $categories = $service->getCategoriesExpense();

        return view('cabinet.profile.categories-expense', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        CategoriesExpense::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Категория добавлена.');
    }

    public function update(Request $request, CategoriesExpense $categoriesExpense)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $categoriesExpense->update($request->only(['name']));

        return redirect()->back()->with('success', 'Категория обновлена.');
    }


    public function destroy(CategoriesExpense $categoriesExpense)
    {
        $categoriesExpense->delete();
        return redirect()->back()->with('success', 'Счёт удалён.');
    }
}
