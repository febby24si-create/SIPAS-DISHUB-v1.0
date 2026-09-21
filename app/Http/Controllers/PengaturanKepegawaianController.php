<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Models\KategoriBup;

class PengaturanKepegawaianController extends Controller
{
    public function index()
    {
        $settings = [
            'kgb_interval_months' => SystemSetting::getSetting('kgb_interval_months'),
            'kp_interval_years' => SystemSetting::getSetting('kp_interval_years'),
            'reminder_kgb_days' => SystemSetting::getSetting('reminder_kgb_days'),
            'reminder_kp_days' => SystemSetting::getSetting('reminder_kp_days'),
            'reminder_bup_days' => SystemSetting::getSetting('reminder_bup_days'),
        ];

        $kategoriBup = KategoriBup::all();

        return view('pengaturan.kepegawaian.index', compact('settings', 'kategoriBup'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'kgb_interval_months' => 'nullable|integer|min:1',
            'kp_interval_years' => 'nullable|integer|min:1',
            'reminder_kgb_days' => 'nullable|integer|min:1',
            'reminder_kp_days' => 'nullable|integer|min:1',
            'reminder_bup_days' => 'nullable|integer|min:1',
        ]);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                SystemSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            } else {
                // If it's submitted as empty, we could remove the setting or keep it null.
                // Keeping it clean by removing it if it's explicitly cleared.
                SystemSetting::where('key', $key)->delete();
            }
        }

        return back()->with('status', 'Pengaturan Kepegawaian berhasil diperbarui.');
    }
}
