<?php

namespace App\Core\View;

class View
{
    protected $layout = 'layouts.app';
    protected $sections = [];
    protected $currentSection = null;

    public function extends($layout)
    {
        $this->layout = $layout;
    }

    public function section($name)
    {
        $this->currentSection = $name;
        ob_start();
    }

    public function endSection()
    {
        if ($this->currentSection) {
            $this->sections[$this->currentSection] = ob_get_clean();
            $this->currentSection = null;
        }
    }

    public function getContent($name)
    {
        return $this->sections[$name] ?? '';
    }

    public function render($viewName, $data = [])
    {
        $data['view'] = $this;
        extract($data);
        
        // Load view first to capture sections
        ob_start();
        require __DIR__ . '/../../../resources/views/' . str_replace('.', '/', $viewName) . '.php';
        ob_end_clean();

        // Now load layout with captured sections
        ob_start();
        require __DIR__ . '/../../../resources/views/layouts/app.php';
        $output = ob_get_clean();

        echo $output;
        exit;
    }
}