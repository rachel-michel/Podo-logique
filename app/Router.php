<?php

namespace App;

class Router
{
  private array $routes = [];

  public function get(string $pattern, callable $handler): void
  {
    $this->addRoute('GET', $pattern, $handler);
  }

  public function post(string $pattern, callable $handler): void
  {
    $this->addRoute('POST', $pattern, $handler);
  }

  public function put(string $pattern, callable $handler): void
  {
    $this->addRoute('PUT', $pattern, $handler);
  }

  public function delete(string $pattern, callable $handler): void
  {
    $this->addRoute('DELETE', $pattern, $handler);
  }

  private function addRoute(string $method, string $pattern, callable $handler): void
  {
    $this->routes[] = [
      'method'  => $method,
      'pattern' => $pattern,
      'handler' => $handler,
    ];
  }

  public function dispatch(string $method, string $uri): void
  {
    foreach ($this->routes as $route) {
      if ($route['method'] !== $method) {
        continue;
      }

      $params = $this->match($route['pattern'], $uri);

      if ($params === null) {
        continue;
      }

      // Call handler
      ($route['handler'])($params);
      return;
    }

    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'error' => 'Route non trouvée']);
  }

  /**
   * Match une URI contre un pattern /api/patients/{id}
   */
  private function match(string $pattern, string $uri): ?array
  {
    // /api/patients/{id} -> #^/api/patients/(?P<id>[^/]+)$#
    $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $pattern);
    $regex = '#^' . $regex . '$#';

    if (!preg_match($regex, $uri, $matches)) {
      return null;
    }

    $params = [];
    foreach ($matches as $key => $value) {
      if (!is_int($key)) {
        $params[$key] = $value;
      }
    }

    return $params;
  }
}
