<?php

namespace App\Commands;

class Serve
{
    public function handle()
    {
        $host = '127.0.0.1';
        $port = '8000';
        
        echo "Starting development server at http://{$host}:{$port}\n";
        echo "Press Ctrl+C to stop\n";
        
        shell_exec("php -S {$host}:{$port} -t public/");
    }
}