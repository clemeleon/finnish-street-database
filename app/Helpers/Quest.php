<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 04 March 2021
 */

namespace App\Helpers;


use App\Controllers\StreetController;
use App\Errors\BadRequestError;
use App\Errors\InternalServerError;
use Exception;

class Quest
{
    private array $routes = [
        '/streets' => [StreetController::class, 'all']
    ];

    public function __construct()
    {
        if ($this->path() !== '/') {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json; charset=UTF-8");
            header("Access-Control-Allow-Methods: GET");
            header("Access-Control-Max-Age: 3600");
            header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
            $this->check();
        }
    }

    private function check(): void {
        $path = $this->path();
        foreach ($this->routes as $key => $route) {
            if (strpos($path, $key) !== false) {
                $temps = explode('/', ltrim(rtrim($path, '/'), '/'));
                $params = [];
                $count = count($temps);
                for ($i = 0; $i < $count; $i++) {
                    if (strpos($key, $temps[$i]) === false) {
                        $params[] = $temps[$i];
                    }
                }
                $this->navigate($route, $params);
                return;
            }
        }
        $this->response(400, 'page doesnt exist!', 'This endpoint has moved or changed!');

    }

    private function path(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    private function response(int $code = 200, string $title = null, $message = null): void
    {
        if ($code === 400) {
            $data = new BadRequestError($title ?? '', $message ?? '');
        } elseif($code === 500) {
            $data = new InternalServerError($title ?? '', $message ?? '');
        } else {
            $data = $message;
        }
        http_response_code($code);
        echo json_encode($data);
        exit();
    }

    private function navigate(array $route, array $params = []): void
    {
        [$class, $method] = $route;
        $data = [400, '', 'Nothing was found!'];
        try {
            $instance = new $class();
            if (isset($instance) && method_exists($instance, $method)) {
                $data = call_user_func_array([$instance, $method], $params);
            }
        } catch (Exception $e) {
            $data = [500, '', ''];
        }
        $this->response(...$data);
    }
}