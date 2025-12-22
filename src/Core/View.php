<?php

namespace App\Core;

class View
{
    public static function render($view, $params = [])
    {
        $config = require __DIR__ . '/../../config/database.php';
        $params['base_url'] = $config['app']['url'];

        extract($params);

        $viewPath = __DIR__ . "/../../views/$view.php";

        if (file_exists($viewPath)) {
            ob_start();
            include $viewPath;
            return ob_get_clean();
        } else {
            return "View $view not found";
        }
    }
}
