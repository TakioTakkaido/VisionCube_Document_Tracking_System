<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Storage::extend('google', function($app, $config) {
            // Initialize the Google Client
            $client = new \Google_Client();
            $client->setClientId($config['client_id']);  // Ensure the config key matches
            $client->setClientSecret($config['client_secret']);  // Ensure the config key matches
            $client->refreshToken($config['refresh_token']);  // Ensure the config key matches
    
            // Create the Google Drive Service
            $service = new \Google_Service_Drive($client);
    
            // Set up the Flysystem Google Drive Adapter
            $adapter = new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter($service, $config['folder_id'] ?? null); // Optional folder ID
    
            // Return the Filesystem instance
            return new \League\Flysystem\Filesystem($adapter);
        });
    }
    

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}