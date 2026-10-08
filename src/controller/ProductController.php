<?php

namespace App\controller;

use App\service\ProductService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use OpenApi\Attributes as OAT;

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

    #[OAT\Put(
        path: '/api/v1/product/{sku}',
        summary: 'Erstellt oder bearbeitet ein Produkt',
        tags: ['Produkte'],
        parameters: [
            new OAT\Parameter(
                name: 'sku',
                in: 'path',
                required: true,
                description: 'sku des Produktes',
                schema: new OAT\Schema(
                    type: 'string',
                    example: '123456'
                )
            )
        ],
        requestBody: new OAT\RequestBody(
            required: true,
            description: 'Daten des Produktes',
            content: new OAT\JsonContent(
                required: ['active', 'id_category', 'name', 'image', 'description', 'price', 'stock'],
                properties: [
                    new OAT\Property(
                        property: 'active',
                        type: 'integer',
                        example: 1
                    ),
                    new OAT\Property(
                        property: 'id_category',
                        nullable: true,
                        type: 'integer',
                        example: 1
                    ),
                    new OAT\Property(
                        property: 'name',
                        type: 'string',
                        example: 'Lego'
                    ),
                    new OAT\Property(
                        property: 'image',
                        type: 'string',
                        example: 'https://www.csbe.ch/resources/themes/csbe/images/logo.svg?m=1595406300'
                    ),
                    new OAT\Property(
                        property: 'description',
                        type: 'string',
                        example: 'Ich bin eine Beschreibung'
                    ),
                    new OAT\Property(
                        property: 'price',
                        type: 'string',
                        example: '3999.99'
                    ),
                    new OAT\Property(
                        property: 'stock',
                        type: 'integer',
                        example: 3
                    )
                ],
                type: 'object'
            )
        ),
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Produkt wurde bearbeitet',
                content: new OAT\JsonContent(
                    type: 'object',
                    properties: [
                        new OAT\Property(
                            property:
                            'id', type:
                            'integer',
                            example: 2),
                        new OAT\Property(
                            property:
                            'sku', type: 'string',
                            example: '123456'
                        ),
                        new OAT\Property(
                            property: 'active',
                            type: 'integer',
                            example: 1
                        ),
                        new OAT\Property(
                            property: 'id_category',
                            type: 'integer',
                            nullable: true,
                            example: 2
                        ),
                        new OAT\Property(
                            property:
                            'name',
                            type: 'string',
                            example: 'Lego'
                        ),
                        new OAT\Property(
                            property: 'image',
                            type:
                            'string',
                            example: 'https://www.csbe.ch/resources/themes/csbe/images/logo.svg?m=1595406300'),
                        new OAT\Property(
                            property: 'description',
                            type: 'string',
                            example: 'Ich bin eine Beschreibung'),
                        new OAT\Property(
                            property: 'price',
                            type: 'number',
                            example: 3999.99),
                        new OAT\Property(
                            property: 'stock',
                            type: 'integer',
                            example: 3)
                    ]
                )
            ),
            new OAT\Response(
                response: 201,
                description: 'Produkt wurde erstellt',
                content: new OAT\JsonContent(
                    type: 'object',
                    properties: [
                        new OAT\Property(property: 'id', type: 'integer', example: 2),
                        new OAT\Property(property: 'sku', type: 'string', example: '123456'),
                        new OAT\Property(property: 'active', type: 'integer', example: 1),
                        new OAT\Property(property: 'id_category', type: 'integer', nullable: true, example: 2),
                        new OAT\Property(property: 'name', type: 'string', example: 'Lego'),
                        new OAT\Property(property: 'image', type: 'string', example: 'https://www.csbe.ch/resources/themes/csbe/images/logo.svg?m=1595406300'),
                        new OAT\Property(property: 'description', type: 'string', example: 'Ich bin eine Beschreibung'),
                        new OAT\Property(property: 'price', type: 'number', example: 3999.99),
                        new OAT\Property(property: 'stock', type: 'integer', example: 3)
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
                            example: 'Invalid active'
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
                            example: 'Category not found'
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

    #[OAT\Delete(
        path: '/api/v1/product/{sku}',
        summary: 'Löscht ein Produkt anhand der sku',
        tags: ['Produkte'],
        parameters: [
            new OAT\Parameter(
                name: 'sku',
                in: 'path',
                required: true,
                description: 'sku des Produktes',
                schema: new OAT\Schema(
                    type: 'string',
                    example: '123456'
                )
            )
        ],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Produkt wurde gelöscht',
            ),
            new OAT\Response(
                response: 400,
                description: 'Ungültige Eingabe',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'error',
                            type: 'string',
                            example: 'Invalid SKU'
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
                description: 'Produkt wurde nicht gefunden',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'not_found',
                            type: 'string',
                            example: 'Product sku not found'
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

    #[OAT\Get(
        path: '/api/v1/product/{sku}',
        summary: 'Gibt ein Produkt anhand der sku zurück',
        tags: ['Produkte'],
        parameters: [
            new OAT\Parameter(
                name: 'sku',
                in: 'path',
                required: true,
                description: 'Produkt sku',
                schema: new OAT\Schema(
                    type: 'string',
                    example: '123456'
                )
            )
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Produkt wurde gefunden',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'product_id',
                            type: 'integer',
                            example: 1
                        ),
                        new OAT\Property(
                            property: 'sku',
                            type: 'string',
                            example: '123456'
                        ),
                        new OAT\Property(
                            property: 'active',
                            type: 'integer',
                            example: 1
                        ),
                        new OAT\Property(
                            property: 'id_category',
                            nullable: true,
                            type: 'integer',
                            example: 1
                        ),
                        new OAT\Property(
                            property: 'name',
                            type: 'string',
                            example: 'Lego'
                        ),
                        new OAT\Property(
                            property: 'image',
                            type: 'string',
                            example: 'https://www.csbe.ch/resources/themes/csbe/images/logo.svg?m=1595406300'
                        ),
                        new OAT\Property(
                            property: 'description',
                            type: 'string',
                            example: 'Ich bin eine Beschreibung'
                        ),
                        new OAT\Property(
                            property: 'price',
                            type: 'string',
                            example: '3999.99'
                        ),
                        new OAT\Property(
                            property: 'stock',
                            type: 'integer',
                            example: 3
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
                            example: 'Invalid SKU'
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
                description: 'Produkt wurde nicht gefunden',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'not_found',
                            type: 'string',
                            example: 'Product not found'
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

    /**
     *  Lists all Products from the Database
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The Response
     * @param array $args The path variables
     * @return ResponseInterface The final response
     */

    #[OAT\Get(
        path: '/api/v1/products',
        summary: 'Gibt alle Produkte zurück',
        tags: ['Produkte'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Alle Produkte wurden ausgegeben',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: 'object',
                        properties: [
                            new OAT\Property(
                                property: 'product_id',
                                type: 'integer',
                                example: 2
                            ),
                            new OAT\Property(
                                property: 'sku',
                                type: 'string',
                                example: '123456'
                            ),
                            new OAT\Property(
                                property: 'active',
                                type: 'integer',
                                example: 1
                            ),
                            new OAT\Property(
                                property: 'id_category',
                                type: 'integer',
                                nullable: true,
                                example: 2
                            ),
                            new OAT\Property(
                                property: 'name',
                                type: 'string',
                                example: 'Lego'
                            ),
                            new OAT\Property(
                                property: 'image',
                                type: 'string',
                                example: 'https://www.csbe.ch/resources/themes/csbe/images/logo.svg?m=1595406300'
                            ),
                            new OAT\Property(
                                property: 'description',
                                type: 'string',
                                example: 'Ich bin eine Beschreibung'
                            ),
                            new OAT\Property(
                                property: 'price',
                                type: 'string',
                                example: '3999.99'
                            ),
                            new OAT\Property(
                                property: 'stock',
                                type: 'integer',
                                example: 3
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
                            example: 'an unexpected error occurred'
                        )
                    ],
                    type: 'object'
                )
            )
        ]
    )]
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
