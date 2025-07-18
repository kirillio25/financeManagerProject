<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;

use App\Services\Cabinet\AccountService;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Http\Requests\Cabinet\AccountRequest;

use App\Models\Account;

class CashAccountController extends Controller
{
    public function index(AccountService $service)
    {
        $accounts = $service->getAccountsWithBalance();
        return view('cabinet.profile.cash-account', compact('accounts'));
    }

    public function update(AccountRequest $request, Account $account)
    {
        $account->update($request->only('name', 'note'));
        return redirect()->back()->with('success', 'Счёт обновлён.');
    }

    public function store(AccountRequest $request)
    {
        auth()->user()->accounts()->create($request->only('name', 'note'));
        return redirect()->back()->with('success', 'Счёт добавлен.');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->back()->with('success', 'Счёт удалён.');
    }
}
