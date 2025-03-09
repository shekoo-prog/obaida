<?php

namespace App\Commands;

class MakeHelper
{
    public function handle($name)
    {
        $template = <<<PHP
<?php

namespace App\Helpers;

class {$name}
{
    // Helper methods here
}
PHP;

        $path = __DIR__ . "/../../app/Helpers/{$name}.php";
        file_put_contents($path, $template);
        echo "Helper created successfully: {$name}.php\n";
    }
}