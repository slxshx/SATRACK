<?php

namespace App\Core;

class Router
{
    private array $getRoutes = [];
    private array $postRoutes = [];

    public function get(string $path, array $handler): void
    {
        $this->getRoutes[$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->postRoutes[$path] = $handler;
    }

    public function dispatch(): void
    {
        // Speichern ob Post oder Get
        $method = $_SERVER['REQUEST_METHOD'];
        // Url speichern
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Wenn post, dann nehme post array sonst get array
        $routes = $method === 'POST' ? $this->postRoutes : $this->getRoutes;

        if (!isset($routes[$uri])) {
            http_response_code(404);
            echo '404 - Seite nicht gefunden.';
            return;
        }

        [$class, $method] = $routes[$uri];

        if (!class_exists($class) || !method_exists($class, $method)) {
            echo '500 - Serverfehler: Ungültiger Handler';
            return;
        }

        (new $class)->$method();
    }
}
