<?php

namespace App\Commands;

use App\Core\Database\Database;

class MigrateFresh
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function handle()
    {
        // Drop all tables
        $this->dropAllTables();
        
        // Run migrations
        (new Migration())->handle();
        
        echo "Database refreshed successfully!\n";
    }

    protected function dropAllTables()
    {
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
        
        $tables = $this->db->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
        
        foreach ($tables as $table) {
            $this->db->query("DROP TABLE IF EXISTS `{$table}`");
            echo "Dropped table: {$table}\n";
        }

        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
    }
}