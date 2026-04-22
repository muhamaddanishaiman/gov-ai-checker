<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    public function analyze($base64, $mimeType = 'image/jpeg')
    {
        $apiKey = env('GEMINI_API_KEY');

        // Added retry mechanism: retry 3 times, with 1000ms (1 second) wait between retries
        $response = Http::retry(3, 1000)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
            [
                "contents" => [
                    [
                        "parts" => [
                            [
                                "text" => "You are an AI bantuan document validator. Identify document type (IC or Salary Slip), validate it, and return ONLY a valid JSON object with document_type (string), valid (boolean), issues (array of strings), suggestions (array of strings). Do not use markdown blocks."
                            ],
                            [
                                "inline_data" => [
                                    "mime_type" => $mimeType,
                                    "data" => $base64
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );

        $data = $response->json();

        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            $jsonString = $data['candidates'][0]['content']['parts'][0]['text'];
            
            // Clean markdown wrappers if any
            $jsonString = str_replace(['```json', '```'], '', $jsonString);
            $jsonString = trim($jsonString);

            $decoded = json_decode($jsonString, true);
            if ($decoded) {
                return $decoded;
            }
        }

        return [
            "document_type" => "Unknown",
            "valid" => false,
            "issues" => ["AI response format error or no text returned.", json_encode($data)],
            "suggestions" => ["Try again later."]
        ];
    }
}
