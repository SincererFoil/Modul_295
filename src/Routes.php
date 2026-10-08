<?php

namespace App;

use App\controller\AuthController;
use App\controller\CategoryController;
use App\controller\ProductController;
use App\database\Database;
use App\database\repository\CategoryRepository;
use App\database\repository\ProductRepository;
use App\jwt\JwtService;
use App\middleware\Middleware;
use App\service\AuthService;
use App\service\CategoryService;
use App\service\ProductService;
use OpenApi\Attributes as OAT;
use Slim\App;


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

        $productRepository = new ProductRepository($database);

        $categoryService = new CategoryService($categoryRepository);

        $productService = new ProductService($productRepository, $categoryRepository);

        $categoryController = new CategoryController($categoryService);

        $productController = new ProductController($productService, $categoryRepository);

        $authController = new AuthController($authenticationService);


        // Initialize Routes

        $app->setBasePath("/api/v1");
        // Auth Routes

        // (Authenticate) Route
        $app->post('/authenticate', [$authController, 'authenticate']);

        // (UnAuthenticate) Route
        $app->delete('/unauthenticate', [$authController, 'unAuthenticate'])
            ->addMiddleware($middleware);

        // Category Routes

        // GET (Categories) Route
        $app->get("/categories", [$categoryController, 'getCategoriesRequest'])
            ->addMiddleware($middleware);

        // GET (Category) Route
        $app->get("/category/{category_id}", [$categoryController, 'getCategory'])
            ->addMiddleware($middleware);

        // POST (Category) Route
        $app->post('/category', [$categoryController, 'createCategory'])
            ->addMiddleware($middleware);

        // PATCH (Category) Route
        $app->patch('/category/{category_id}', [$categoryController, 'updateCategory'])
            ->addMiddleware($middleware);

        // DELETE (Category) Route
        $app->delete('/category/{category_id}', [$categoryController, 'deleteCategory'])
            ->addMiddleware($middleware);


        // Product Routes


        // PUT (product) Route
        $app->put('/product/{sku}', [$productController, 'putProductRequest'])
            ->addMiddleware($middleware);

    }
}