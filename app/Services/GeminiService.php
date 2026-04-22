<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    public function analyze($base64)
    {
        $apiKey = env('GEMINI_API_KEY');

        $response = Http::post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro-vision:generateContent?key={$apiKey}",
            [
                "contents" => [
                    [
                        "parts" => [
                            [
                                "text" => "You are an AI bantuan document validator. Identify document type (IC or Salary Slip), validate it, and return JSON with document_type, valid, issues, suggestions."
                            ],
                            [
                                "inline_data" => [
                                    "mime_type" => "image/jpeg",
                                    "data" => $base64
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );

        return $response->json();
    }
}