<?php

namespace App\Core;

class View
{
    protected $layout = 'layouts.app';
    protected $data = [];
    protected $sections = [];
    protected $currentSection = null;

    public function render($view, $data = [])
    {
        $this->data = array_merge($this->data, $data);
        $content = $this->renderView($view);
        
        if ($this->layout) {
            $this->data['content'] = $content;
            return $this->renderView($this->layout);
        }
        
        return $content;
    }

    protected function renderView($view)
    {
        $viewPath = $this->getViewPath($view);
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View {$view} not found");
        }

        ob_start();
        extract($this->data);
        include $viewPath;
        return ob_get_clean();
    }

    protected function getViewPath($view)
    {
        $view = str_replace('.', '/', $view);
        return dirname(__DIR__, 2) . "/resources/views/{$view}.php";
    }

    public function section($name)
    {
        $this->currentSection = $name;
        ob_start();
    }

    public function endSection()
    {
        if (!$this->currentSection) {
            throw new \Exception("No section started");
        }

        $this->sections[$this->currentSection] = ob_get_clean();
        $this->currentSection = null;
    }

    public function getSection($name)
    {
        return $this->sections[$name] ?? '';
    }

    public function extends($layout)
    {
        $this->layout = $layout;
    }
}