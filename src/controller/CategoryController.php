<?php

namespace App\controller;
use App\service\CategoryService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use OpenApi\Attributes as OAT;

class CategoryController {

    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }


    /**
     * Creates a new Category from the request data
     *
     * Possible response codes:
     * 201 Created -> Successful creation
     * 400 Bad Request -> for invalid inputs
     * 500 Internal Server Error -> for server issues
     *
     * @param ServerRequestInterface $request The incomming HTTP request.
     * @param ResponseInterface $response The Response Object
     * @param array $args The route argumments provided
     * @return ResponseInterface The json result containing the result.
     *
     */
    public function createCategory(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        // Executes the createCategory in CategoryService and saves the service response in $result
        $result = $this->categoryService->createCategory($request);

        // Checks if the response array contains an Error
        if (isset($result["error"])) {
            $code = 400;
            // Checks if the response array has a fatal unexpected error
        } elseif (isset($result["fatal error"])) {
            $code = 500;
        } else {
            // Sets the normal "Created" code
            $code = 201;
        }
        // Array into a JSON response object
        $response->getBody()->write(json_encode($result, true));
        $response = $response->withStatus($code)->withHeader('Content-Type', 'application/json');
        // Returns the response to the client
        return $response;

    }


    /**
     *  This method returns all Categories from the Database
     *
     * Possible response codes:
     * 200 OK -> Successful Response
     * 500 Internal Server Error -> for server issues
 *
     * @param ServerRequestInterface  $request Users request to the endpoint
     * @param ResponseInterface $response  The given response for the user by the endpoint
     * @param array $args possible Path variables in a assiociative array
     * @return ResponseInterface Returns the Response to the user
     */


    public function getCategoriesRequest(ServerRequestInterface  $request, ResponseInterface $response, array $args): ResponseInterface {

        // Calls the getAllCategories method from the Category Repository
        // and saves the response in the variable $categories
        $result = $this->categoryService->getAllCategories();

        // Sets the Status to 200 = OK
        $code = 200;
        // Checks if the response has a fatal error
        if (isset($result["fatal error"])) {
            // Sets the Status to 500 = Internal Server Error
            $code = 500;
        }

        // Writes the given response as a json into the response body
        $response->getBody()->write(json_encode($result));
        $response = $response->withStatus($code)->withHeader('Content-Type', 'application/json');
        // Returns the Result Response
        return $response;
    }


    /**
     *  Updates an existing Category from the provided data
     *
     * Possible response codes:
     * 200 OK -> Successful Update
     * 400 Bad Request -> for invalid inputs
     * 500 Internal Server Error -> for server issues
     *
     * @param ServerRequestInterface $request The incomming request
     * @param ResponseInterface $response The HTTP response object
     * @param array $args Route argumments provided
     * @return ResponseInterface The JSON response containing the result
     */
    public function updateCategory(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        $result = $this->categoryService->updateCategory($request, $args);

        // Sets the Status to 200 = OK
        $code = 200;

        if (isset($result["error"])) {
            // Sets the Status code to 400 = Bad Request
            $code = 400;
        }

        if (isset($result["fatal error"])) {
            // Sets the status code to 500 = Internal Server Error
            $code = 500;
        }

        // Converts the array into a JSON array
        $response->getBody()->write(json_encode($result, true));
        // Sets content-type and status code
        $response = $response->withStatus($code)->withHeader('Content-Type', 'application/json');
        return $response;
    }

    /**
     *  Gets an existing Category from the provided data
     *
     * Possible response codes:
     * 200 OK -> Successful response
     * 400 Bad Request -> for invalid inputs
     * 500 Internal Server Error -> for server issues
     *
     * @param ServerRequestInterface $request The incomming request
     * @param ResponseInterface $response The HTTP response object
     * @param array $args Route argumments provided
     * @return ResponseInterface The JSON response containing the result
     */
    public function getCategory(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $result = $this->categoryService->getCategory($args);

        // Sets the Status to 200 = OK
        $code = 200;

        if (isset($result["error"])) {
            // Sets the Status code to 400 = Bad Request
            $code = 400;
        }

        if (isset($result["fatal error"])) {
            // Sets the status code to 500 = Internal Server Error
            $code = 500;
        }

        // Converts the array into a JSON array
        $response->getBody()->write(json_encode($result, true));
        // Sets content-type and status code
        $response = $response->withStatus($code)->withHeader('Content-Type', 'application/json');
        return $response;
    }


    /**
     *  Deletes an existing Category from the provided data
     *
     * Possible response codes:
     * 204 No Content -> Successful deletion with no response body
     * 400 Bad Request -> for invalid inputs
     * 500 Internal Server Error -> for server issues
     *
     * @param ServerRequestInterface $request The incomming request
     * @param ResponseInterface $response The HTTP response object
     * @param array $args Route argumments provided
     * @return ResponseInterface The JSON response containing the errors
     */
    public function deleteCategory(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $result = $this->categoryService->deleteCategory($args);

        // Sets the Status to 204 = No Content
        $code = 204;
        $exception = false;

        if (isset($result["error"])) {
            // Sets the Status code to 400 = Bad Request
            $code = 400;
            $exception = true;
        }

        if (isset($result["fatal error"])) {
            // Sets the status code to 500 = Internal Server Error
            $code = 500;
            $exception = true;
        }

        if ($exception) {
            // Converts the array into JSON if an error was thrown
            $response->getBody()->write(json_encode($result));
            $response = $response->withHeader('Content-Type', 'application/json');
        }

        // Sets content-type and status code
        $response = $response->withStatus($code);
        return $response;
    }


}
