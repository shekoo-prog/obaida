<?php

namespace App\Commands;

class CacheClear
{
    public function handle()
    {
        $cachePath = __DIR__ . '/../../storage/cache';
        
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        
        echo "Cache cleared successfully!\n";
    }
}