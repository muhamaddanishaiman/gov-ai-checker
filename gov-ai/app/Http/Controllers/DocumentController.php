<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class DocumentController extends Controller
{
    public function check(Request $request, GeminiService $gemini)
    {
        $file = $request->file('document');
        $base64 = base64_encode(file_get_contents($file));

        $response = $gemini->analyze($base64);

        return response()->json($response);
    }
}
