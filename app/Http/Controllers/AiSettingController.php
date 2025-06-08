<?php

namespace App\Http\Controllers;

use App\Models\AiSetting;
use Illuminate\Http\Request;

class AiSettingController extends Controller
{
    /**
     * Show the AI settings page.
     */
    public function index()
    {
        // Fetch existing settings
        $settings = AiSetting::all()->pluck('value', 'name');

        // Set default values if they don't exist
        $apiKeys = json_decode($settings->get('gemini_api_keys', '[]'), true);
        $strategy = $settings->get('gemini_api_strategy', 'random');
        $model = $settings->get('gemini_model', 'gemini-1.5-flash-latest');
        $language = $settings->get('ai_language', 'English');
        $tone = $settings->get('ai_tone', 'Professional and helpful');

        // Expanded list of available models based on Gemini documentation
        $availableModels = [
            'gemini-2.5-pro-latest',
            'gemini-2.5-flash-latest',
            'gemini-2.0-flash-001',
            'gemini-1.5-pro-002',
            'gemini-1.5-flash-latest',
            'gemini-1.0-pro',
        ];

        $languageOptions = ['English', 'Indonesian', 'Japanese', 'Spanish'];
        $toneOptions = ['Professional and helpful', 'Casual and friendly', 'Formal and academic', 'Enthusiastic and motivational'];

        return view('settings.ai.index', compact('apiKeys', 'strategy', 'model', 'language', 'tone', 'availableModels', 'languageOptions', 'toneOptions'));
    }

    /**
     * Store the AI settings.
     */
    public function store(Request $request)
    {
        $request->validate([
            'api_keys' => 'nullable|array',
            'api_keys.*' => 'nullable|string',
            'strategy' => 'required|in:random,round-robin',
            'model' => 'required|string', // Updated validation
            'language' => 'required|string|max:50',
            'tone' => 'required|string|max:100',
        ]);

        // Filter out any empty keys before saving
        $filteredKeys = $request->api_keys ? array_filter($request->api_keys) : [];

        // An array of settings to update or create
        $settingsToStore = [
            'gemini_api_keys' => json_encode(array_values($filteredKeys)),
            'gemini_api_strategy' => $request->strategy,
            'gemini_model' => $request->model,
            'ai_language' => $request->language,
            'ai_tone' => $request->tone,
        ];

        foreach ($settingsToStore as $name => $value) {
            AiSetting::updateOrCreate(['name' => $name], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'AI Settings have been saved successfully!');
    }
}
