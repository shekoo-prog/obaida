<?php

namespace App\Core\Helpers;

class Helper
{
    public static function view($name, $data = [])
    {
        $view = new \App\Core\View\View();
        return $view->render($name, $data);
    }

    public static function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'null':
            case '(null)':
                return null;
        }

        return $value;
    }

    public static function debug($value)
    {
        echo '<pre>';
        var_dump($value);
        echo '</pre>';
        exit;
    }
}
