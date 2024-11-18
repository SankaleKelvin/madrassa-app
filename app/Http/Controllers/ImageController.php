<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImageController extends Controller
{
    public function show($path)
    {
        // Ensure the path is clean and secure
        $path = str_replace('..', '', $path);
        $fullPath = storage_path('app/public/' . $path);

        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Return the file with proper headers
        $response = new BinaryFileResponse($fullPath);
        $response->headers->set('Content-Type', mime_content_type($fullPath));
        $response->headers->set('Cache-Control', 'private, max-age=3600');

        return $response;
    }
}
