<?php

namespace App\controller;
use App\service\CategoryService;
use App\service\JwtService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CategoryController {

    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function createCategory($request, $response, $args): ResponseInterface {
        $result = $this->categoryService->createCategory($request);

        if (isset($result["error"])) {
            $code = 400;
        } elseif (isset($result["fatal error"])) {
            $code = 500;
        } else {
            $code = 201;
        }

        $response->getBody()->write(json_encode($result, true));
        $response = $response->withStatus($code)->withHeader('Content-Type', 'application/json');
        return $response;

    }


    /**
     *  This method returns all Categories from the Database
     *
     * @param RequestHandlerInterface $request Users request to the endpoint
     * @param ResponseInterface $response  The given response for the user by the endpoint
     * @param array $args possible Path variables in a assiociative array
     * @return ResponseInterface Returns the Response to the user
     */
    public function getCategoriesRequest(RequestHandlerInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        // Calls the getAllCategories method from the Category Repository
        // and saves the response in the variable $categories
        $categories = $this->categoryService->getAllCategories();

        // Checks if the response has a fatal error
        if (isset($categories["fatal error"])) {
            $response->getBody()->write(json_encode($categories));
            // Sets the Status to 500 = Internal Server Error
            $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
            // Returns the Error Response
            return $response;
        }

        // Writes the given response as a json into the response body
        $response->getBody()->write(json_encode($categories));
        // Sets the Status to 200 = OK
        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        // Returns the Result Response
        return $response;
    }

//    public function updateCategory($request, $response, $args): ResponseInterface {
//        $this->categoryService
//    }



}
