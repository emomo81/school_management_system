<?php

namespace App\Core;

class Controller
{
    public function render($view, $params = [])
    {
        return View::render($view, $params);
    }

    public function redirect($path)
    {
        // Adjust for subdirectory
        $config = require __DIR__ . '/../../config/database.php';
        $baseUrl = $config['app']['url'];
        header("Location: $baseUrl$path");
        exit;
    }

    public function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
