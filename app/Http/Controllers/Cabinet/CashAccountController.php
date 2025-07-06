<?php

namespace App\Http\Controllers\Cabinet;

use app\Http\Controllers\Controller;

use App\Services\Cabinet\AccountService;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\Account;

class CashAccountController extends Controller
{
    public function index(AccountService $service)
    {
        $accounts = $service->getAccountsWithBalance();

        return view('cabinet.profile.cash-account', compact('accounts'));
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'note' => 'nullable|string',
        ]);

        $account->update($request->only(['name', 'note']));

        return redirect()->back()->with('success', 'Счёт обновлён.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'note' => 'nullable|string',
        ]);

        auth()->user()->accounts()->create([
            'name' => $request->name,
            'note' => $request->note,
        ]);

        return redirect()->back()->with('success', 'Счёт добавлен.');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->back()->with('success', 'Счёт удалён.');
    }
}
