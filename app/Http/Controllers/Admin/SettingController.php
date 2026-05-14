<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings =
            AppSetting::query()
                ->pluck('value', 'key');

        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }

    public function update(
        UpdateSettingsRequest $request
    ): RedirectResponse {

        foreach ($request->validated() as $key => $value) {

            AppSetting::query()
                ->updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
        }

        return back()->with(
            'success',
            'Settings updated successfully.'
        );
    }
}