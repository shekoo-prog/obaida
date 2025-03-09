<?php

require_once __DIR__ . '/vendor/autoload.php';

if ($argc < 2) {
    die("Usage: php command.php [command] [options]\n");
}

$command = $argv[1];

$commands = [
    'migrate' => \App\Commands\Migration::class,
    'migrate:fresh' => \App\Commands\MigrateFresh::class,
    'migrate:rollback' => \App\Commands\MigrateRollback::class,
    'make:migration' => \App\Commands\MakeMigration::class,
    'db:seed' => \App\Commands\DbSeed::class,
    'key:generate' => \App\Commands\KeyGenerate::class,
    'make:controller' => \App\Commands\MakeController::class,
    'make:model' => \App\Commands\MakeModel::class,
    'make:middleware' => \App\Commands\MakeMiddleware::class,
    'make:view' => \App\Commands\MakeView::class,
    'make:command' => \App\Commands\MakeCommand::class,
    'make:helper' => \App\Commands\MakeHelper::class,
    'make:test' => \App\Commands\MakeTest::class,
    'cache:clear' => \App\Commands\CacheClear::class,
    'route:list' => \App\Commands\RouteList::class,
    'serve' => \App\Commands\Serve::class,
    'app:install' => \App\Commands\AppInstall::class,
];

if (!isset($commands[$command])) {
    die("Unknown command: {$command}\n");
}

$commandClass = $commands[$command];
$instance = new $commandClass();

$commandsNeedingName = [
    'make:controller',
    'make:model',
    'make:middleware',
    'make:migration',
    'make:view',
    'make:command',
    'make:helper',
    'make:test'
];

if (in_array($command, $commandsNeedingName)) {
    if ($argc < 3) {
        die("Please provide a name for the {$command}\n");
    }
    $instance->handle($argv[2]);
} else {
    $instance->handle();
}