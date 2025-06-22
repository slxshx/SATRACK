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
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Normalisieren der URI
        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }

        $routes = $method === 'POST' ? $this->postRoutes : $this->getRoutes;

        $params = [];

        // Versuchen, die Route mit einer Regex zu matchen
        foreach ($routes as $path => $handler) {
            // Wenn die Route Platzhalter enthält
            if (strpos($path, '{') !== false) {
                // Compile die Route (erstellt Regex)
                $compiled = $this->compileRouting($path);

                if (preg_match($compiled['pattern'], $uri, $matches)) {
                    // Parameter extrahieren
                    foreach ($compiled['paramNames'] as $index => $name) {
                        $params[$name] = $matches[$index + 1];
                    }
                    // Handler ausführen
                    [$class, $method] = $handler;
                    (new $class())->$method($params);
                    return;
                }
            } elseif ($path === $uri) {
                // Wenn die Route exakt übereinstimmt
                [$class, $method] = $handler;
                (new $class())->$method();
                return;
            }
        }

        // Wenn keine Route gefunden wurde
        http_response_code(404);
        echo '404 - Seite nicht gefunden.';
    }

    private function compileRouting(string $path): array
    {
        $paramNames = [];

        // Ersetze Platzhalter wie {id} durch eine RegEx-Gruppe
        $pattern = preg_replace_callback('/\{(\w+)\}/', function ($matches) use (&$paramNames) {
            $paramNames[] = $matches[1];       // z. B. "id"
            return '(\w+)';                     // Platzhalter für alphanumerische Werte
        }, $path);

        // Um den ganzen Ausdruck herum ein RegEx bauen
        $pattern = '#^' . $pattern . '$#';

        return [
            'pattern' => $pattern,
            'paramNames' => $paramNames
        ];
    }
}
