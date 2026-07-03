<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return response()->json($setting);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'website_name' => 'required|max:255',
            'logo' => 'nullable|string',
            'favicon' => 'nullable|string',
            'hero_title' => 'nullable|string',
            'hero_subtitle' => 'nullable|string',
            'whatsapp_number' => 'nullable|string',
            'discord_url' => 'nullable|string',
            'youtube_url' => 'nullable|string',
            'instagram_url' => 'nullable|string',
            'tiktok_url' => 'nullable|string',
        ]);

        $setting = Setting::first();

        if ($setting) {
            $setting->update($validated);
        } else {
            $setting = Setting::create($validated);
        }

        return response()->json($setting);
    }
}
