<?php

namespace App\Commands;

class AppInstall
{
    public function handle()
    {
        echo "Installing application...\n";

        // Create necessary directories
        $dirs = [
            'storage/cache',
            'storage/logs',
            'storage/uploads',
            'resources/views',
            'database/migrations',
            'database/seeds'
        ];

        foreach ($dirs as $dir) {
            $path = __DIR__ . '/../../' . $dir;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
                echo "Created directory: {$dir}\n";
            }
        }

        // Generate application key
        (new KeyGenerate())->handle();

        // Run migrations
        (new Migration())->handle();

        echo "Installation completed!\n";
    }
}