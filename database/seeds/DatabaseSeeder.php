<?php

namespace Database\Seeds;

class DatabaseSeeder
{
    protected $seeders = [
        RoleSeeder::class,
        UserSeeder::class
    ];

    public function run()
    {
        foreach ($this->seeders as $seeder) {
            echo "Running: " . basename(str_replace('\\', '/', $seeder)) . "\n";
            (new $seeder())->run();
        }
    }
}