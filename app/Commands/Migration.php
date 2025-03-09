<?php

namespace App\Commands;

use App\Core\Database\Database;

class Migration
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function handle()
    {
        $migrationFiles = glob(__DIR__ . '/../../database/migrations/*.sql');
        
        foreach ($migrationFiles as $file) {
            try {
                $sql = file_get_contents($file);
                $this->db->query($sql);
                echo "Migrated: " . basename($file) . "\n";
            } catch (\Exception $e) {
                echo "Error in " . basename($file) . ": " . $e->getMessage() . "\n";
            }
        }
    }
}