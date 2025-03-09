<?php

namespace App\Commands;

class MakeView
{
    public function handle($name)
    {
        $parts = explode('.', $name);
        $path = __DIR__ . '/../../resources/views/';
        
        // Create directories if needed
        if (count($parts) > 1) {
            $path .= implode('/', array_slice($parts, 0, -1)) . '/';
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }

        $filename = end($parts) . '.php';
        $fullPath = $path . $filename;

        $template = "<?php\n\n?>\n\n<div>\n    <!-- {$name} view content -->\n</div>";
        
        file_put_contents($fullPath, $template);
        echo "View created successfully: {$name}\n";
    }
}