<?php

namespace App;

use App\controller\AuthController;
use App\controller\CategoryController;
use App\database\Database;
use App\database\repository\CategoryRepository;
use App\middleware\Middleware;
use App\service\AuthService;
use App\service\CategoryService;
use App\service\JwtService;
use Slim\App;
use OpenApi\Attributes as OAT;


#[OAT\Info(
    version: '1.0.0',
    title: 'ÜK-295 LB1 REST API',
)]
class Routes {
    public static function start(App $app): void {
        $database = new Database();
        $jwtService = new JwtService();
        $middleware = new Middleware($jwtService);
        $authenticationService = new AuthService($jwtService);
        $categoryRepository = new CategoryRepository($database);
        $categoryService = new CategoryService($categoryRepository);
        $categoryController = new CategoryController($categoryService);
        $authController = new AuthController($authenticationService);


        // Initialize Routes

        $app->setBasePath("/api/v1");
        // Auth Routes

        // (Authenticate) Route
        $app->post('/authenticate', [$authController, 'authenticate']);

        // (UnAuthenticate) Route
        $app->delete('/unauthenticate', [$authController, 'unAuthenticate']);


        // Category Routes

        // GET (Categories) Route
        $app->get("/categories", [$categoryController, 'getCategoriesRequest'])
        ->addMiddleware($middleware);

        // POST (Category) Route
        $app->post('/category', [$categoryController, 'createCategory'])
        ->addMiddleware($middleware);

        // PATCH (Category) Route
        $app->patch('/category/{category_id}', [$categoryController, 'updateCategory']);
    }
}