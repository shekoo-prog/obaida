<?php

namespace App\Commands;

class MakeController
{
    public function handle($name)
    {
        $template = <<<PHP
<?php

namespace App\Controllers;

class {$name}Controller extends BaseController
{
    public function index()
    {
        return \$this->view('{$name}.index');
    }

    public function show(\$id)
    {
        return \$this->view('{$name}.show');
    }

    public function create()
    {
        return \$this->view('{$name}.create');
    }

    public function store()
    {
        // Handle create logic
    }

    public function edit(\$id)
    {
        return \$this->view('{$name}.edit');
    }

    public function update(\$id)
    {
        // Handle update logic
    }

    public function delete(\$id)
    {
        // Handle delete logic
    }
}
PHP;

        $path = __DIR__ . "/../../app/Controllers/{$name}Controller.php";
        file_put_contents($path, $template);
        echo "Controller created successfully: {$name}Controller.php\n";
    }
}