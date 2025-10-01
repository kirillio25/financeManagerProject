<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\Cabinet\ProfileExportService;
use App\Services\Cabinet\ProfileImportService;
use App\Services\Cabinet\ProfileService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Cabinet\ProfileImportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('cabinet.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request, ProfileService $service): RedirectResponse
    {
        $service->update($request);
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request, ProfileService $service): RedirectResponse
    {
        $service->destroy($request);
        return Redirect::to('/');
    }

    public function exportSql(Request $request, ProfileExportService $service): StreamedResponse
    {
        return $service->exportSql($request->user());
    }

    public function importSql(ProfileImportRequest $request, ProfileImportService $service): RedirectResponse
    {
        $success = $service->import($request->file('sql_file'), auth()->id());

        return back()->with(
            $success ? 'success' : 'error',
            $success ? 'Импорт завершён' : 'Импорт не удался. См. лог.'
        );
    }


}
