<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\GlobalSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Get all global settings grouped.
     */
    public function index(): JsonResponse
    {
        $settings = GlobalSetting::all()->groupBy('group')->map(function ($items) {
            return $items->map(fn($s) => [
                'key'         => $s->key,
                'value'       => $s->value,
                'description' => $s->description,
            ]);
        });

        return response()->json(['settings' => $settings]);
    }

    /**
     * Update global settings.
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string'],
            'settings.*.value' => ['required'],
        ]);

        $admin = $request->user();

        foreach ($validated['settings'] as $item) {
            $old = GlobalSetting::getValue($item['key']);
            GlobalSetting::setValue($item['key'], $item['value']);

            if ($old !== $item['value']) {
                AdminAuditLog::record(
                    $admin->id,
                    'setting_changed',
                    'GlobalSetting',
                    null,
                    ['key' => $item['key'], 'value' => $old],
                    ['key' => $item['key'], 'value' => $item['value']]
                );
            }
        }

        return response()->json(['message' => 'Settings updated successfully.']);
    }
}
