<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class DocumentController extends Controller
{
    public function check(Request $request, GeminiService $gemini)
    {
        $file = $request->file('document');
        if (!$file) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $base64 = base64_encode(file_get_contents($file));
        $mimeType = $file->getMimeType();

        $response = $gemini->analyze($base64, $mimeType);

        return response()->json($response);
    }
}
