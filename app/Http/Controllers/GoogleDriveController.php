<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Storage;

class GoogleDriveController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/credentials.json'));
        $this->client->addScope(Drive::DRIVE_FILE);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $service = new Drive($this->client);
        
        // Authenticate user
        if ($request->has('code')) {
            $this->client->authenticate($request->get('code'));
            $request->session()->put('google_drive_token', $this->client->getAccessToken());
        }

        if ($request->session()->has('google_drive_token')) {
            $this->client->setAccessToken($request->session()->get('google_drive_token'));
        }

        // Handle token refresh
        if ($this->client->isAccessTokenExpired()) {
            $refreshToken = $this->client->getRefreshToken();
            $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
            $request->session()->put('google_drive_token', $this->client->getAccessToken());
        }

        $file = $request->file('image');
        $filePath = $file->getPathName();
        $fileName = $file->getClientOriginalName();
        $fileMetadata = new Drive\DriveFile([
            'name' => $fileName,
            'parents' => ['your-folder-id']
        ]);

        $content = file_get_contents($filePath);
        $file = $service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        $fileId = $file->id;
        $fileUrl = "https://drive.google.com/uc?export=view&id={$fileId}";

        // Guardar la URL del archivo en la base de datos o donde sea necesario

        return response()->json(['message' => 'File uploaded successfully', 'url' => $fileUrl], 201);
    }

    public function redirectToGoogle()
    {
        return redirect()->away($this->client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        $this->client->authenticate($request->get('code'));
        $request->session()->put('google_drive_token', $this->client->getAccessToken());

        return redirect('/'); // Redirige a la página principal o donde desees
    }

}
