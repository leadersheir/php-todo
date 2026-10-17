<?php

class Router {
    private array $routes = [];

    public function get($path, $handler) {
        $this->routes['GET'][$path] = $handler;
    }

    public function post($path, $handler) {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch($uri, $method) {
        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];

            $handler();

            return;
        }

        http_response_code(404);
        echo "404 NOT FOUND";
    }
}