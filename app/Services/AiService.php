<?php

namespace App\Services;

use App\Models\AiSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class AiService
{
    private const API_BASE_URL = 'https://generativelanguage.googleapis.com/v1beta/models/';
    private array $settings;

    public function __construct()
    {
        // Cache settings for a short duration to avoid multiple DB queries per request.
        $this->settings = Cache::remember('ai_settings', 60, function () {
            return AiSetting::all()->pluck('value', 'name')->toArray();
        });
    }

    /**
     * Generates text using the configured Gemini model and API key.
     *
     * @throws \Exception
     */
    public function generateText(string $prompt, string $context): string
    {
        $apiKey = $this->getApiKey();
        if (!$apiKey) {
            throw new \Exception("No active AI API key is configured. Please set one in the admin settings.");
        }

        $model = $this->getSetting('gemini_model', 'gemini-1.5-flash-latest');
        $language = $this->getSetting('ai_language', 'English');
        $tone = $this->getSetting('ai_tone', 'professional and helpful');
        
        $apiUrl = self::API_BASE_URL . $model . ':generateContent?key=' . $apiKey;

        // Construct a more robust prompt with explicit rules for the AI
        $fullPrompt =
            "**Primary Rule:** Generate the entire response strictly in the following language: '{$language}'.\n" .
            "**Secondary Rule:** Adopt a '{$tone}' tone of voice.\n\n" .
            "**Additional Rule:** Concise and to the point response output.\n\n" .
            "**Project Context:**\n---\n{$context}\n---\n\n" .
            "**User's Request:**\n{$prompt}";

        try {
            $response = Http::timeout(60)->post($apiUrl, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $fullPrompt]
                        ]
                    ]
                ]
            ]);

            $response->throw();

            $generatedText = $response->json('candidates.0.content.parts.0.text');

            if (is_null($generatedText)) {
                throw new \Exception('Failed to extract generated text from the API response. The response might have been filtered for safety reasons.');
            }

            return $generatedText;

        } catch (RequestException $e) {
            $errorBody = $e->response->json('error.message') ?? $e->getMessage();
            throw new \Exception("AI API Error: " . $errorBody);
        } catch (\Exception $e) {
            throw new \Exception("An unexpected error occurred while contacting the AI service: " . $e->getMessage());
        }
    }

    /**
     * Retrieves an API key based on the configured strategy.
     */
    private function getApiKey(): ?string
    {
        $keysJson = $this->getSetting('gemini_api_keys');
        if (!$keysJson) {
            return null;
        }

        $keys = json_decode($keysJson, true);
        if (empty($keys)) {
            return null;
        }

        $strategy = $this->getSetting('gemini_api_strategy', 'random');

        if ($strategy === 'round-robin') {
            $lastIndex = Cache::get('ai_api_key_last_index', -1);
            $nextIndex = ($lastIndex + 1) % count($keys);
            Cache::put('ai_api_key_last_index', $nextIndex);
            return $keys[$nextIndex];
        }

        return Arr::random($keys);
    }

    /**
     * Helper to get a setting value from the cached array.
     */
    private function getSetting(string $name, mixed $default = null): mixed
    {
        return $this->settings[$name] ?? $default;
    }
}
