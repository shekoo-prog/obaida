<?php

namespace App\Commands;

class KeyGenerate
{
    public function handle()
    {
        $key = bin2hex(random_bytes(32));
        $envFile = __DIR__ . '/../../.env';

        if (file_exists($envFile)) {
            $content = file_get_contents($envFile);
            $content = preg_replace('/APP_KEY=.*/', "APP_KEY={$key}", $content);
            file_put_contents($envFile, $content);
        }

        echo "Application key set successfully: {$key}\n";
    }
}