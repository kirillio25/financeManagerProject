<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\DebtRequest;
use App\Models\Debt;
use App\Services\Cabinet\DebtsService;

class DebtController extends Controller
{
    public function index(DebtsService $service)
    {
        return view('cabinet.debts.my-debts', [
            'debts' => $service->getMyDebts()
        ]);
    }

    public function store(DebtRequest $request, DebtsService $service)
    {
        $service->store($request);
        return redirect()->back()->with('success', 'Долг добавлен');
    }

    public function update(DebtRequest $request, Debt $debt, DebtsService $service)
    {
        $service->update($request, $debt);
        return redirect()->back()->with('success', 'Долг обновлён');
    }

    public function destroy(Debt $debt, DebtsService $service)
    {
        $service->delete($debt);
        return redirect()->back()->with('success', 'Долг удалён.');
    }

    public function toggleStatus(Debt $debt, DebtsService $service)
    {
        $service->toggleStatus($debt);
        return back();
    }
}
