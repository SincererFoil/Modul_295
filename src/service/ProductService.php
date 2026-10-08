<?php

namespace App\service;

use App\database\repository\CategoryRepository;
use App\database\repository\ProductRepository;
use App\product\Product;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ProductService {

    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;


    /**
     *  Constructs the 2 Repositories
     *
     * @param ProductRepository $productRepository An instance of a Product repository
     * @param CategoryRepository $categoryRepository An instance of a category repository
     */
    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     *  This Method handles product put requests,
     *  It validates all data and executes an insert or update in the Database
     *
     * @param ServerRequestInterface $request The request with the Body
     * @param array $args The path Parameters
     * @return array The result
     */
    public function putProductRequest(ServerRequestInterface $request, array $args): array {

        $body = $request->getParsedBody();

        if (!is_array($body)) {
            return ["error" => "Invalid request body"];
        }

        // An array with all required arg keys
        $requiredArguments = [
            "active",
            "id_category",
            "name",
            "image",
            "description",
            "price",
            "stock"
        ];

        // Checks for each required keys if the value exists in the request body
        foreach ($requiredArguments as $requiredArgument) {
            if (!array_key_exists($requiredArgument, $body)) {
                return ["error" => "Missing required argument $requiredArgument"];
            }
        }

        // Checks if the active parameter is 0 or 1
        if (!in_array($body["active"], [0, 1], true)) {
            return ["error" => "Invalid active"];
        }

        $active = (int) $body["active"];

        // Checks if the stock isn't an int and less than 0
        if (!is_int($body["stock"]) || $body["stock"] < 0) {
            return ["error" => "Invalid stock"];
        }

        // Checks if the name isn't a string or if the name has no characters
        if (!is_string($body["name"]) || trim($body["name"]) === "") {
            return ["error" => "Invalid name"];
        }

        if (!is_string($body["image"]) || trim($body["image"]) === "") {
            return ["error" => "Invalid image"];
        }

        if (!is_string($body["description"]) || trim($body["description"]) === "") {
            return ["error" => "Invalid description"];
        }

        if (!is_numeric($body["price"]) || (float) $body["price"] < 0) {
            return ["error" => "Invalid price"];
        }

        $price = (float) $body["price"];

        if ($price < 0) {
            return ["error" => "Invalid price"];
        }

        if (!is_int($body["id_category"])) {
            return ["error" => "Invalid category"];
        }

        $categoryId = (int) $body["id_category"];

        $sku = $args['sku'] ?? null;

        // Checks if the sku isn't a string and if the sku has no characters
        if (!is_string($sku) || trim($sku) === "") {
            return ["error" => "Invalid SKU"];
        }

        // Creates a new Product object with the values
        $product = new Product(
            null,
            $body["name"],
            $sku,
            $active,
            $categoryId,
            $body["image"],
            $body["description"],
            $price,
            $body["stock"]
            );

        try {
            // Checks if the category id exists
            if (!$this->categoryRepository->existsById($categoryId)) {
                return ["not_found" => "Category not found"];
            }
             // Checks if the product exists
            if ($this->productRepository->productExists($sku)) {
                // Executes an update to update the product
                $result = $this->productRepository->updateProduct($product);
            } else {
                // Executes an insert to create and save the new product
                $result = $this->productRepository->createProduct($product);
                $result["created"] = true;
            }
            // Returns the result
            return $result;

            // If something goes wrong, it will send a 500 initial server error
        } catch (\Throwable $ex) {
            return ["fatal_error" => "an unexpected error occurred"];
        }



    }

}
