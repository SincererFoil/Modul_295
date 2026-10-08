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
    public function putProduct(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
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


    /**
     *  Handles a Delete Product API Request
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     * @param array $args The arguments (Path parameters)
     * @return ResponseInterface reurn the final result
     */
    public function deleteProduct(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        // Calls the deleteProduct service method with the params
        // and saves the result
        $result = $this->productService->deleteProduct($args);
        // Sets default response code to 204 = No Content
        $code = 204;

        // Checks if the result key 'deleted' exists
        if (isset($result["deleted"])) {
            // It will return the 204 status with no response body
            return $response->withStatus($code);
        }

        // Checks if the result contains an error
        if (isset($result["error"])) {
            // Sets the code to 400 = Bad Request
            $code = 400;
        }

        // Checks if the result contains the key "not_found"
        if (isset($result["not_found"])) {
            // Sets the status code to 404 = Not Found
            $code = 404;
        }

        // Checks if the result contains a fatal_error
        if (isset($result["fatal_error"])) {
            // Sets the code to 500 = Internal Server Error
            $code = 500;
        }

        // Writes the response body with the result in json format
        $response->getBody()->write(json_encode($result));
        // Sets the response code and content-type
        $response = $response->withStatus($code)->withHeader('Content-Type', 'application/json');
        // Returns the response
        return $response;
    }

    /**
     *  Gets a Specific product from the database
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The Response
     * @param array $args The path parameters
     * @return ResponseInterface The final result
     */
    public function getProduct(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        // Saves the result of the service method getProduct in result
        $result = $this->productService->getProduct($args);
        // Sets the default code to 200
        $code = 200;

        // Checks if the result contains an error
        if (isset($result["error"])) {
            // Sets the code to 400 = Bad request
            $code = 400;
        }

        // Checks if the reszlt contains a not found error
        if (isset($result["not_found"])) {
            // Sets the code to 404 = Not Found
            $code = 404;
        }

        // Checks if the result contains a fatal error
        if (isset($result["fatal_error"])) {
            // Sets the code to 500 = Internal Server Error
            $code = 500;
        }
        // Writes the Body with the result
        $response->getBody()->write(json_encode($result));

        // Sets the status code and Content-Type
        $response = $response->withStatus($code)->withHeader('Content-Type', 'application/json');
        // Returns the response
        return $response;
    }


    public function listProducts(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        // Saves the result of listProducts service method in results
        $result = $this->productService->listProducts();
        // Sets default code to 200 = OK
        $code = 200;

        // Checks if the result contains an error
        if (isset($result["error"])) {
            // Sets the code to 400 = Bad request
            $code = 400;
        }

        // Checks if the result contains a fatal error
        if (isset($result["fatal_error"])) {
            // Sets the response code to 500 = Internal Server Error
            $code = 500;
        }

        // Writes the response body in a json format
        $response->getBody()->write(json_encode($result));
        // Sets the response status and content-type
        $response = $response->withStatus($code)->withHeader('Content-Type', 'application/json');
        return $response;
    }

}
