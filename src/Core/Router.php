<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        // Remove query string
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }

        // Handle subdirectories in URL (e.g., /school_system/public)
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && strpos($path, $scriptName) === 0) {
            $path = substr($path, strlen($scriptName));
        }
        if ($path === '')
            $path = '/';

        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            // Check for dynamic routes (basic implementation)
            foreach ($this->routes[$method] as $route => $handler) {
                // Convert route params {id} to regex
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_]+)', $route);
                $pattern = "@^" . $pattern . "$@D";

                if (preg_match($pattern, $path, $matches)) {
                    // Filter out integer keys
                    $params = array_filter($matches, function ($key) {
                        return !is_numeric($key);
                    }, ARRAY_FILTER_USE_KEY);

                    if (is_array($handler)) {
                        $controller = new $handler[0]();
                        $action = $handler[1];
                        return call_user_func([$controller, $action], $params);
                    }
                    return call_user_func($handler, $params);
                }
            }

            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        if (is_array($callback)) {
            $controller = new $callback[0]();
            $action = $callback[1];
            return call_user_func([$controller, $action]);
        }

        echo call_user_func($callback);
    }
}
