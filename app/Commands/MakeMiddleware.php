<?php

namespace App\Commands;

class MakeMiddleware
{
    public function handle($name)
    {
        $template = <<<PHP
<?php

namespace App\Middleware;

class {$name}Middleware extends Middleware
{
    public function handle(\$request, \$next)
    {
        // Add your middleware logic here
        
        return \$next(\$request);
    }
}
PHP;

        $path = __DIR__ . "/../../app/Middleware/{$name}Middleware.php";
        file_put_contents($path, $template);
        echo "Middleware created successfully: {$name}Middleware.php\n";
    }
}