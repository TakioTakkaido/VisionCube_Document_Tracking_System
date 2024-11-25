<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Drive extends Model {
    use HasFactory;

    protected $fillable = [
        'email',
        'verified_at',
        'disabled',
        'canDocument',
        'canReport',
        'refresh_token'
    ];

    public function documentFolder(){
        return $this->hasOne(Folder::class, 'drive_id');
    }

    public function reportFolder(){
        return $this->hasOne(Folder::class, 'drive_id');
    }

    public function rootFolder(){
        return $this->hasOne(Folder::class, 'drive_id');
    }

    // Folder Management Functions
    // COE Document Tracking System
    public function token(){
        $client_id = \config('services.google.client_id');
        $client_secret = \config('services.google.client_secret');
        $refresh_token = \config('services.google.refresh_token');
        $response = Http::post('https://oauth2.googleapis.com/token', [
            'client_id' =>  $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $this->refresh_token,
            'grant_type' => 'refresh_token'
        ]);

        $access_token = json_decode((string)$response->getBody(), true)['access_token'];
        return $access_token;
    }

    

    public function createRootFolder(){
        $metadata = [
            'name' => "COE Document Tracking System",
            'mimeType' => 'application/vnd.google-apps.folder',
        ];

        $fileStore = Http::withToken($this->token())
        ->withHeaders([
            'Content-Type' => 'application/json',
        ])
        ->post('https://www.googleapis.com/drive/v3/files', $metadata);

        // dd($fileStore);
        if ($fileStore->successful()){
            $rootFolder = Folder::create([
                'type' => 'Root',
                'folder_id' => $fileStore->json('id'),
                'drive_id' => $this->id,
            ]);

            $this->rootFolder()->save($rootFolder);
        }
    }
    public function getDocumentFolder($year, $month)
    {
        return $this->getFolder($year, $month, 'Documents');
    }
    
    public function getReportFolder($year, $month)
    {
        return $this->getFolder($year, $month, 'Reports');
    }
    
    private function getFolder($year, $month, $type)
    {
        // Ensure the root folder exists
        $rootFolder = $this->ensureFolderExists(null, 'Root');
    
        // Ensure the year folder exists
        $yearFolder = $this->ensureFolderExists($rootFolder, 'Subroot', $year);
    
        // Ensure the month folder exists
        $monthFolder = $this->ensureFolderExists($yearFolder, 'Subroot', $year, $month);
    
        // Ensure the type folder exists (e.g., 'Documents' or 'Reports')
        $typeFolder = $this->ensureFolderExists($monthFolder, $type);
    
        return $typeFolder->folder_id;
    }
    
    private function ensureFolderExists($parentFolder, $type, $year = null, $month = null)
    {
        if ($type === 'Root' && !$parentFolder) {
            // Handle the Root folder separately
            return $this->handleRootFolder();
        }
    
        // Search for the folder in the database
        $existingFolder = $parentFolder->subFolders->first(function ($folder) use ($type, $year, $month) {
            return $folder->type === $type &&
                $folder->year == $year &&
                $folder->month == $month;
        });
    
        // Validate and recreate folder if it exists but is invalid on Google Drive
        if ($existingFolder && !$this->folderExists($existingFolder->folder_id)) {
            $parentFolder->subFolders()->detach($existingFolder);
            $existingFolder->delete();
            $existingFolder = null;
        }
    
        // Create a new folder if it doesn't exist
        if (!$existingFolder) {
            $folderName = $this->getFolderName($type, $year, $month);
            $metadata = [
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$parentFolder->folder_id],
            ];
    
            $fileStore = Http::withToken($this->token())
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://www.googleapis.com/drive/v3/files', $metadata);
    
            if ($fileStore->successful()) {
                $existingFolder = Folder::create([
                    'type' => $type,
                    'year' => $year,
                    'month' => $month,
                    'folder_id' => $fileStore->json('id'),
                    'drive_id' => $this->id,
                    'parent_id' => $parentFolder->id,
                ]);
    
                $parentFolder->subFolders()->save($existingFolder);
            }
        }
    
        return $existingFolder;
    }
    
    private function handleRootFolder()
    {
        if ($this->rootFolder()->first() == null) {
            $this->createRootFolder();
        }
    
        $rootFolder = $this->rootFolder()->first();
    
        if (!$this->folderExists($rootFolder->folder_id)) {
            $rootFolder->delete();
            $this->createRootFolder();
            $rootFolder = $this->rootFolder()->first();
        }
    
        return $rootFolder;
    }
    
    private function getFolderName($type, $year = null, $month = null)
    {
        switch ($type) {
            case 'Root':
                return 'RootFolder'; // Adjust this if your root folder has a specific name
            case 'Subroot':
                return $month ?: $year;
            case 'Documents':
            case 'Reports':
                return $type;
            default:
                return 'Unknown';
        }
    }
    
    private function folderExists(string $folderId): bool
    {
        $apiKey = env('GOOGLE_API_KEY');
        $url = "https://www.googleapis.com/drive/v3/files/{$folderId}?fields=id&key={$apiKey}";
    
        $response = Http::get($url);
    
        if ($response->successful()) {
            return true;
        }
    
        if ($response->status() === 404) {
            return false;
        }
    
        return false;
    }
    
}
