import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel([
            'resources/js/app.js',
            'resources/js/sidepanel-active.js',
            'resources/js/sidepanel-archived.js',
            'resources/js/uploadform.js',
            'resources/css/dashboard.css',
            'resources/css/register_login.css',
        ])
    ],
});
