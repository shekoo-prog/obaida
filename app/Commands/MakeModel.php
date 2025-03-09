<?php

namespace App\Commands;

class MakeModel
{
    public function handle($name)
    {
        $template = <<<PHP
<?php

namespace App\Models;

use App\Core\Database\BaseModel;

class {$name} extends BaseModel
{
    protected \$table = '{{table}}';
    protected \$fillable = [];
    protected \$hidden = [];
}
PHP;

        $table = strtolower($name) . 's';
        $template = str_replace('{{table}}', $table, $template);

        $path = __DIR__ . "/../../app/Models/{$name}.php";
        file_put_contents($path, $template);
        echo "Model created successfully: {$name}.php\n";
    }
}