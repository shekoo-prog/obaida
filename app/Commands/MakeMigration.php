<?php

namespace App\Commands;

class MakeMigration
{
    public function handle($name)
    {
        $timestamp = date('Y_m_d_His');
        $filename = $timestamp . '_' . strtolower($name) . '.sql';
        $rollbackFilename = $timestamp . '_' . strtolower($name) . '.rollback.sql';

        $path = __DIR__ . '/../../database/migrations/' . $filename;
        $rollbackPath = __DIR__ . '/../../database/migrations/' . $rollbackFilename;

        $template = "-- Migration for {$name}\n\n";
        $rollbackTemplate = "-- Rollback for {$name}\n\n";

        file_put_contents($path, $template);
        file_put_contents($rollbackPath, $rollbackTemplate);

        echo "Created Migration: {$filename}\n";
        echo "Created Rollback: {$rollbackFilename}\n";
    }
}