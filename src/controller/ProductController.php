<?php

namespace App\controller;

use App\service\ProductService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ProductController {

    private ProductService $productService;

    /**
     *  Constructor Initializes the Product service
     *
     * @param ProductService $productService The instance of a product service
     */
    public function __construct(ProductService $productService) {
        $this->productService = $productService;
    }


    /**
     *  Handles a Put Product Request
     *
     * @param ServerRequestInterface $request The Request with the body
     * @param ResponseInterface $response The Response
     * @param array $args The path variables etc.
     * @return ResponseInterface Returns the final result.
     */
    public function putProductRequest(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        // Sends the request to the putProductRequest method in the Product Service class
        // Saves the result in the variable $result
        $result = $this->productService->putProductRequest($request, $args);

        // Sets the default code to 200 = OK
        $code = 200;

        // if the result contains the key "created"
        if (isset($result["created"])) {
            // It sets the code to 201 = Created
            $code = 201;
            // It removes the information key Created for the final response
            unset($result["created"]);
        }

        // If the result contains an error
        if (isset($result["error"])) {
            // It sets the code to 400 = Bad Request
            $code = 400;
        }

        // If the result contains an error (not found)
        if (isset($result["not_found"])) {
            // It sets the code to 404 = Not Found
            $code = 404;
        }

        // If the result contains a fatal_error
        if (isset($result["fatal_error"])) {
            // It sets the code to 500 = Internal Server Error
            $code = 500;
        }
        // It writes the result in json format into the response
        $response->getBody()->write(json_encode($result));
        // gives the response a staus and content type
        $response = $response->withStatus($code)->withHeader('Content-Type', 'application/json');
        // Returns the Response
        return $response;
    }

}
