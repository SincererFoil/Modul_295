<?php
use App\Routes;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../src/Routes.php';

try {
    $app = AppFactory::create();

// Adds a Middleware
    $app->addBodyParsingMiddleware();

// Starts the routes and initializes all services etc. defined in "Routes"
    Routes::start($app);

// This method traverses the application middleware stack
// and then sends the resultant Response object to the HTTP client
    $app->run();
} catch (Throwable $e) {
    //Logs in php console for debug
    error_log($e->getMessage());
    // Sets status
    http_response_code(500);
    header('Content-Type: application/json');
    // Sends fallback message
    echo json_encode([
        'fatal_error' => 'an unexpected error occurred'
    ]);
}