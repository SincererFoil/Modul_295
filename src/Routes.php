<?php

namespace App;

use App\controller\AuthController;
use App\database\Database;
use App\middleware\Middleware;
use App\Middleware\TestMw;
use App\service\AuthenticationService;
use App\service\JwtService;
use Slim\App;

class Routes {
    public static function start(App $app): void {

        $database = new Database();
        $jwtService = new JwtService();
        $middleware = new Middleware($jwtService);
        $authenticationService = new AuthenticationService($jwtService);
        $authController = new AuthController($authenticationService);

        $app->post('/api/v1/authenticate', [$authController, 'authenticate']);
        $app->get('/', function ($request, $response, $args) {
            $response->getBody()->write(json_encode(["Geht" => "Nicht"]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        });
    }
}