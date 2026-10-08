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

    #[OAT\Post(
        path: '/api/v1/category',
        summary: 'Erstellt eine Kategorie',
        tags: ['Kategorien'],
        requestBody: new OAT\RequestBody(
            required: true,
            description: 'Daten der Kategorie',
            content: new OAT\JsonContent(
                required: ['name', 'active'],
                properties: [
                    new OAT\Property(
                        property: 'name',
                        type: 'string',
                        maxLength: 500,
                        example: 'Kleidung'
                    ),
                    new OAT\Property(
                        property: 'active',
                        type: 'integer',
                        enum: [0, 1],
                        example: 1
                    )
                ],
                type: 'object'
            )
        ),
        responses: [
            new OAT\Response(
                response: 201,
                description: 'Kategorie wurde erstellt',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'id',
                            type: 'integer',
                            example: 1
                        ),
                        new OAT\Property(
                            property: 'name',
                            type: 'string',
                            example: 'Kleidung'
                        ),
                        new OAT\Property(
                            property: 'active',
                            type: 'integer',
                            example: 1
                        )
                    ]
                )
            ),
            new OAT\Response(
                response: 400,
                description: 'Ungültige Eingabe',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'error',
                            type: 'string',
                            example: "Wrong parameter type 'active' or 'name'"
                        )
                    ]
                )
            ),
            new OAT\Response(
                response: 401,
                description: 'Nicht authentifiziert',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'error',
                            type: 'string',
                            example: 'Missing token'
                        )
                    ],
                    type: 'object'
                )
            ),
            new OAT\Response(
                response: 500,
                description: 'Unbekannter Serverfehler',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'fatal_error',
                            type: 'string',
                            example: "an unexpected error occurred"
                        )
                    ]
                )
            )
        ]
    )]
    public function createCategory(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        // Executes the createCategory in CategoryService and saves the service response in $result
        $result = $this->categoryService->createCategory($request);

        // Checks if the response array contains an Error
        if (isset($result["error"])) {
            $code = 400;
            // Checks if the response array has a fatal unexpected error
        } elseif (isset($result["fatal_error"])) {
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


    #[OAT\Get(
        path: '/api/v1/categories',
        summary: 'Gibt alle kategorien zurück',
        tags: ['Kategorien'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Kategorien wurden gefunden',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: 'object',
                        properties: [
                            new OAT\Property(
                                property: 'id',
                                type: 'integer',
                                example: 1
                            ),
                            new OAT\Property(
                                property: 'name',
                                type: 'string',
                                example: 'Kleidung'
                            ),
                            new OAT\Property(
                                property: 'active',
                                type: 'integer',
                                example: 1
                            )
                        ]
                    )
                )
            ),
            new OAT\Response(
                response: 401,
                description: 'Nicht authentifiziert',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'error',
                            type: 'string',
                            example: 'Missing token'
                        )
                    ],
                    type: 'object'
                )
            ),
            new OAT\Response(
                response: 500,
                description: 'Unbekannter Serverfehler',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'fatal_error',
                            type: 'string',
                            example: "an unexpected error occurred"
                        )
                    ]
                )
            )
        ]
    )]


    public function getCategories(ServerRequestInterface  $request, ResponseInterface $response, array $args): ResponseInterface {

        // Calls the getAllCategories method from the Category Repository
        // and saves the response in the variable $categories
        $result = $this->categoryService->getAllCategories();

        // Sets the Status to 200 = OK
        $code = 200;
        // Checks if the response has a fatal error
        if (isset($result["fatal_error"])) {
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
     * 404 Not Found -> if a category not exists / content not exists
     * 400 Bad Request -> for invalid inputs
     * 500 Internal Server Error -> for server issues
     *
     * @param ServerRequestInterface $request The incomming request
     * @param ResponseInterface $response The HTTP response object
     * @param array $args Route argumments provided
     * @return ResponseInterface The JSON response containing the result
     */


    #[OAT\Patch(
        path: '/api/v1/category/{category_id}',
        summary: 'Ändert eine Kategorie anhand der ID',
        tags: ['Kategorien'],
        parameters: [
            new OAT\Parameter(
                name: 'category_id',
                in: 'path',
                required: true,
                description: 'ID der Kategorie',
                schema: new OAT\Schema(
                    type: 'integer',
                    example: 1
                )
            )
        ],
        requestBody: new OAT\RequestBody(
            required: true,
            description: 'Daten der Kategorie',
            content: new OAT\JsonContent(
                required: ['name', 'active'],
                properties: [
                    new OAT\Property(
                        property: 'name',
                        type: 'string',
                        maxLength: 500,
                        example: 'Kleidung'
                    ),
                    new OAT\Property(
                        property: 'active',
                        type: 'integer',
                        enum: [0, 1],
                        example: 1
                    )
                ],
                type: 'object'
            )
        ),
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Kategorie wurde geändert',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'id',
                            type: 'integer',
                            example: 1
                        ),
                        new OAT\Property(
                            property: 'name',
                            type: 'string',
                            example: "Kleidung",
                        ),
                        new OAT\Property(
                            property: 'active',
                            type: 'integer',
                            example: 1
                        )
                    ]
                )
            ),
            new OAT\Response(
                response: 400,
                description: 'Ungültige Eingabe',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'error',
                            type: 'string',
                            example: "Missing required parameter 'name' or 'active'"
                        )
                    ]
                )
            ),
            new OAT\Response(
                response: 401,
                description: 'Nicht authentifiziert',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'error',
                            type: 'string',
                            example: 'Missing token'
                        )
                    ],
                    type: 'object'
                )
            ),
            new OAT\Response(
                response: 404,
                description: 'Kategorie wurde nicht gefunden',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'not_found',
                            type: 'string',
                            example: "Category does not exist"
                        )
                    ]
                )
            ),
            new OAT\Response(
                response: 500,
                description: 'Unbekannter Serverfehler',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'fatal_error',
                            type: 'string',
                            example: "an unexpected error occurred"
                        )
                    ]
                )
            )
        ]
    )]
    public function updateCategory(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {


        $result = $this->categoryService->updateCategory($request, $args);

        // Sets the Status to 200 = OK
        $code = 200;

        if (isset($result["not_found"])) {
            // Sets code to 404 not found
            $code = 404;
        }

        if (isset($result["error"])) {
            // Sets the Status code to 400 = Bad Request
            $code = 400;
        }

        if (isset($result["fatal_error"])) {
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
     * 404 Not Found -> when a category id not exists.
     * 400 Bad Request -> for invalid inputs
     * 500 Internal Server Error -> for server issues
     *
     * @param ServerRequestInterface $request The incomming request
     * @param ResponseInterface $response The HTTP response object
     * @param array $args Route argumments provided
     * @return ResponseInterface The JSON response containing the result
     */


    #[OAT\Get(
        path: '/api/v1/category/{category_id}',
        summary: 'Gibt eine kategorie anhand der id zurück',
        tags: ['Kategorien'],
        parameters: [
            new OAT\Parameter(
                name: 'category_id',
                in: 'path',
                required: true,
                description: 'ID der Kategorie',
                schema: new OAT\Schema(
                    type: 'integer',
                    example: 1
                )
            )
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Kategorie wurde gefunden'
                ,
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'id',
                            type: 'integer',
                            example: 1
                        ),
                        new OAT\Property(
                            property: 'name',
                            type: 'string',
                            example: 'Kleidung'
                        ),
                        new OAT\Property(
                            property: 'active',
                            type: 'integer',
                            example: 1
                        )
                    ]
                )
            ),
            new OAT\Response(
                response: 400,
                description: 'Ungültige Eingabe',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'error',
                            type: 'string',
                            example: "Wrong parameter type 'category_id'"
                        )
                    ]
                )
            ),
            new OAT\Response(
                response: 401,
                description: 'Nicht authentifiziert',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'error',
                            type: 'string',
                            example: 'Missing token'
                        )
                    ],
                    type: 'object'
                )
            ),
            new OAT\Response(
                response: 404,
                description: 'Kategorie wurde nicht gefunden',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'not_found',
                            type: 'string',
                            example: "Category does not exist"
                        )
                    ]
                )
            ),
            new OAT\Response(
                response: 500,
                description: 'Unbekannter Serverfehler',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                           property: 'fatal_error',
                            type: 'string',
                            example: "an unexpected error occurred"
                        )
                    ]
                )
            )
        ]
    )]

    public function getCategory(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $result = $this->categoryService->getCategory($args);

        // Sets the Status to 200 = OK
        $code = 200;

        if (isset($result["not_found"])) {
            // Sets code to 404 not found
            $code = 404;
        }

        if (isset($result["error"])) {
            // Sets the Status code to 400 = Bad Request
            $code = 400;
        }

        if (isset($result["fatal_error"])) {
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
     * 404 Not Found -> if a category id does not exist
     * 400 Bad Request -> for invalid inputs
     * 500 Internal Server Error -> for server issues
     *
     * @param ServerRequestInterface $request The incomming request
     * @param ResponseInterface $response The HTTP response object
     * @param array $args Route argumments provided
     * @return ResponseInterface The JSON response containing the errors
     */


    #[OAT\Delete(
        path: '/api/v1/category/{category_id}',
        summary: 'Löscht eine kategorie anhand der ID',
        tags: ['Kategorien'],
        parameters: [
            new OAT\Parameter(
                name: 'category_id',
                in: 'path',
                required: true,
                description: 'ID der Kategorie',
                schema: new OAT\Schema(
                    type: 'integer',
                    example: 1
                )
            )
        ],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Kategorie wurde gelöscht',
            ),
            new OAT\Response(
                response: 400,
                description: 'Ungültige Eingabe',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'error',
                            type: 'string',
                            example: "Wrong parameter type 'category_id'"
                        )
                    ]
                )
            ),
            new OAT\Response(
                response: 401,
                description: 'Nicht authentifiziert',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'error',
                            type: 'string',
                            example: 'Missing token'
                        )
                    ],
                    type: 'object'
                )
            ),
            new OAT\Response(
                response: 404,
                description: 'Kategorie wurde nicht gefunden',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'not_found',
                            type: 'string',
                            example: "Category does not exist"
                        )
                    ]
                )
            ),
            new OAT\Response(
                response: 409,
                description: 'Es existieren noch Produkte mit dieser Kategorie',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'conflict',
                            type: 'string',
                            example: "Category is still used by products"
                        )
                    ]
                )
            ),
            new OAT\Response(
                response: 500,
                description: 'Unbekannter Serverfehler',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'fatal_error',
                            type: 'string',
                            example: "an unexpected error occurred"
                        )
                    ]
                )
            )
        ]
    )]

    public function deleteCategory(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $result = $this->categoryService->deleteCategory($args);

        // Sets the Status to 204 = No Content
        $code = 204;
        $exception = false;

        if (isset($result["not_found"])) {
            // Sets code to 404 not found
            $code = 404;
            $exception = true;
        }

        if (isset($result["error"])) {
            // Sets the Status code to 400 = Bad Request
            $code = 400;
            $exception = true;
        }

        if (isset($result["fatal_error"])) {
            // Sets the status code to 500 = Internal Server Error
            $code = 500;
            $exception = true;
        }

        // Checks if the results contains a conflict
        if (isset($result["conflict"])) {
            // Sets the response code to 409 = Conflict
            $code = 409;
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
