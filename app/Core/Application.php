<?php

namespace App\Core;

class Application
{
    private static $instance = null;
    private array $container = [];
    private string $basePath;

    private function __construct(string $basePath)
    {
        $this->basePath = $basePath;
        $this->bootstrapApplication();
    }

    public static function getInstance(string $basePath = ''): self
    {
        if (self::$instance === null) {
            self::$instance = new self($basePath);
        }
        return self::$instance;
    }

    private function bootstrapApplication(): void
    {
        $this->loadEnvironmentVariables();
        $this->registerBaseBindings();
        $this->registerBaseServices();
    }

    private function loadEnvironmentVariables(): void
    {
        if (file_exists($this->basePath . '/.env')) {
            \Dotenv\Dotenv::createImmutable($this->basePath)->load();
        }
    }

    private function registerBaseBindings(): void
    {
        $this->container['app'] = $this;
        $this->container['config'] = [];
        $this->container['base_path'] = $this->basePath;
    }

    private function registerBaseServices(): void
    {
        foreach (glob($this->basePath . '/config/*.php') as $config) {
            $this->container['config'][basename($config, '.php')] = require $config;
        }
    }

    public function bind(string $key, $value): void
    {
        $this->container[$key] = $value;
    }

    public function get(string $key)
    {
        return $this->container[$key] ?? null;
    }

    public function getBasePath(string $path = ''): string
    {
        return $this->basePath . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }

    private function __clone() {}
    private function __wakeup() {}
}