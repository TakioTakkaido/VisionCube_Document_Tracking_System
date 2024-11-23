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

    public function getDocumentFolder($year, $month){
        // Create Root Folder if it doesn't exist
        if ($this->rootFolder()->first() == null) {
            $this->createRootFolder();
        }

        $rootFolder = $this->rootFolder()->first();

        // Create the Subroot Folder (for the year)
        // dd($rootFolder->subFolders()->get());
        $yearFolder = $rootFolder->subFolders->first(function ($subFolderByYear) use ($year) {
            return $subFolderByYear->year == $year && $subFolderByYear->month == null;
        });


        if (!$yearFolder) {
            // Upload new folder
            $metadata = [
                'name' => $year,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$rootFolder->folder_id]
            ];

            $fileStore = Http::withToken($this->token())
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://www.googleapis.com/drive/v3/files', $metadata);

            if ($fileStore->successful()) {
                $yearFolder = Folder::create([
                    'type' => 'Subroot',
                    'year' => $year,
                    'folder_id' => $fileStore->json('id'),
                    'drive_id' => $this->id,
                    'parent_id' => $rootFolder->id
                ]);

                $rootFolder->subFolders()->save($yearFolder);
            }
        }

        // Create the Sub sub root Folder (for the month)
        $monthFolder = $yearFolder->subFolders->first(function ($subFolderByMonth) use ($year, $month) {
            return $subFolderByMonth->year == $year && $subFolderByMonth->month == $month;
        });

        if (!$monthFolder) {
            // Upload new folder
            $metadata = [
                'name' => $month,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$yearFolder->folder_id]
            ];

            $fileStore = Http::withToken($this->token())
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://www.googleapis.com/drive/v3/files', $metadata);

            if ($fileStore->successful()) {
                $monthFolder = Folder::create([
                    'type' => 'Subroot',
                    'year' => $year,
                    'month' => $month,
                    'folder_id' => $fileStore->json('id'),
                    'drive_id' => $this->id,
                    'parent_id' => $yearFolder->id
                ]);

                $yearFolder->subFolders()->save($monthFolder);
            }
        }

        // Create Document Folder
        $documentFolder = $monthFolder->subFolders->first(function ($subFolder) {
            return $subFolder->type === 'Documents';
        });

        if (!$documentFolder) {
            // Upload new folder
            $metadata = [
                'name' => "Documents",
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$monthFolder->folder_id]
            ];

            $fileStore = Http::withToken($this->token())
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://www.googleapis.com/drive/v3/files', $metadata);

            if ($fileStore->successful()) {
                $documentFolder = Folder::create([
                    'type' => 'Documents',
                    'year' => $year,
                    'month' => $month,
                    'folder_id' => $fileStore->json('id'),
                    'drive_id' => $this->id,
                    'parent_id' => $monthFolder->id
                ]);

                $monthFolder->subFolders()->save($documentFolder);
                
            }
        }

        return $documentFolder->folder_id;

    }

    public function createReportFolder($year, $month){
        // Create Root Folder if it doesn't exist
        if ($this->rootFolder()->first() == null) {
            $this->createRootFolder();
        }

        $rootFolder = $this->rootFolder()->first();

        // Create the Subroot Folder (for the year)
        // dd($rootFolder->subFolders()->get());
        $yearFolder = $rootFolder->subFolders->first(function ($subFolderByYear) use ($year) {
            return $subFolderByYear->year == $year && $subFolderByYear->month == null;
        });


        if (!$yearFolder) {
            // Upload new folder
            $metadata = [
                'name' => $year,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$rootFolder->folder_id]
            ];

            $fileStore = Http::withToken($this->token())
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://www.googleapis.com/drive/v3/files', $metadata);

            if ($fileStore->successful()) {
                $yearFolder = Folder::create([
                    'type' => 'Subroot',
                    'year' => $year,
                    'folder_id' => $fileStore->json('id'),
                    'drive_id' => $this->id,
                    'parent_id' => $rootFolder->id
                ]);

                $rootFolder->subFolders()->save($yearFolder);
            }
        }

        // Create the Sub sub root Folder (for the month)
        $monthFolder = $yearFolder->subFolders->first(function ($subFolderByMonth) use ($year, $month) {
            return $subFolderByMonth->year == $year && $subFolderByMonth->month == $month;
        });

        if (!$monthFolder) {
            // Upload new folder
            $metadata = [
                'name' => $month,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$yearFolder->folder_id]
            ];

            $fileStore = Http::withToken($this->token())
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://www.googleapis.com/drive/v3/files', $metadata);

            if ($fileStore->successful()) {
                $monthFolder = Folder::create([
                    'type' => 'Subroot',
                    'year' => $year,
                    'month' => $month,
                    'folder_id' => $fileStore->json('id'),
                    'drive_id' => $this->id,
                    'parent_id' => $yearFolder->id
                ]);

                $yearFolder->subFolders()->save($monthFolder);
            }
        }

        // Create Document Folder
        $documentFolder = $monthFolder->subFolders->first(function ($subFolder) {
            return $subFolder->type === 'Reports';
        });

        if (!$documentFolder) {
            // Upload new folder
            $metadata = [
                'name' => "Reports",
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$monthFolder->folder_id]
            ];

            $fileStore = Http::withToken($this->token())
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://www.googleapis.com/drive/v3/files', $metadata);

            if ($fileStore->successful()) {
                $reportFolder = Folder::create([
                    'type' => 'Reports',
                    'year' => $year,
                    'month' => $month,
                    'folder_id' => $fileStore->json('id'),
                    'drive_id' => $this->id,
                    'parent_id' => $monthFolder->id
                ]);

                $monthFolder->subFolders()->save($documentFolder);
                
            }
        }

        return $reportFolder->folder_id;
    }
}
