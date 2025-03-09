<?php

namespace App\Commands;

class MakeTest
{
    public function handle($name)
    {
        $template = <<<PHP
<?php

namespace Tests;

class {$name}Test extends TestCase
{
    public function test_example()
    {
        \$this->assertTrue(true);
    }
}
PHP;

        $path = __DIR__ . "/../../tests/{$name}Test.php";
        file_put_contents($path, $template);
        echo "Test created successfully: {$name}Test.php\n";
    }
}