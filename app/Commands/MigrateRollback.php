<?php

namespace App\Commands;

use App\Core\Database\Database;

class MigrateRollback
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function handle()
    {
        $migrations = array_reverse(glob(__DIR__ . '/../../database/migrations/*.sql'));
        
        foreach ($migrations as $migration) {
            $rollbackFile = str_replace('.sql', '.rollback.sql', $migration);
            
            if (file_exists($rollbackFile)) {
                try {
                    $sql = file_get_contents($rollbackFile);
                    $this->db->query($sql);
                    echo "Rolled back: " . basename($migration) . "\n";
                } catch (\Exception $e) {
                    echo "Error rolling back " . basename($migration) . ": " . $e->getMessage() . "\n";
                }
            }
        }
    }
}