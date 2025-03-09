<?php

namespace App\Commands;

class MakeCommand
{
    public function handle($name)
    {
        $template = <<<PHP
<?php

namespace App\Commands;

class {$name}
{
    public function handle()
    {
        // Command logic here
    }
}
PHP;

        $path = __DIR__ . "/{$name}.php";
        file_put_contents($path, $template);
        echo "Command created successfully: {$name}.php\n";
    }
}