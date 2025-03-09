<?php

namespace App\Commands;

use App\Core\Database\Database;

class DbSeed
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function handle()
    {
        $seedFiles = glob(__DIR__ . '/../../database/seeds/*.php');
        
        foreach ($seedFiles as $file) {
            require_once $file;
            $className = 'Database\\Seeds\\' . basename($file, '.php');
            
            if (class_exists($className)) {
                $seeder = new $className();
                $seeder->run();
                echo "Seeded: " . basename($file) . "\n";
            }
        }
    }
}