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
        // Casts the image url to a string
        $image = (string) $body["image"];

        // Checks if the Image url is not greater than 1000 characters
        if (strlen($image) > 1000) {
            return ["error" => "Image URL must not exceed 1000 characters"];
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

        if ($body["id_category"] !== null && !is_int($body["id_category"])) {
            return ["error" => "Invalid category"];
        }

        $categoryId = $body["id_category"];

        $sku = $args['sku'] ?? null;

        // Checks if the sku isn't a string and if the sku has no characters
        if (!is_string($sku) || trim($sku) === "") {
            return ["error" => "Invalid SKU"];
        }

        if (strlen($sku) > 100) {
            return ["error" => "SKU must have less than 100 characters"];
        }

        if (strlen($body["name"]) > 500) {
            return ["error" => "Name must have less than 500 characters"];
        }

        $price = (string) $body["price"];

        // Validates that the price is numeric not negative and has at most 2 decimal places
        if (!is_numeric($price) || $price < 0 || strlen($price) > 66 || round((float)$price, 2) != (float)$price) {
            return ["error" => "Invalid price"];
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
            if ($categoryId !== null) {
                // Checks if the category id exists
                if (!$this->categoryRepository->existsById($categoryId)) {
                    return ["not_found" => "Category not found"];
                }
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


    /**
     *  Deletes an Existing product from the Database
     *
     * @param array $args the parameters
     * @return array The result of the request
     */
    public function deleteProduct(array $args): array {
        // if the arg value from 'sku' not exists (invalid array key) then it sets $sku to null
        $sku = $args['sku'] ?? null;

        // Checks if the sku is a string and isn't empty
        if (!is_string($sku) || trim($sku) === "") {
            return ["error" => "Invalid SKU"];
        }

        try {
            // Checks if a product with the sku exists
            if (!$this->productRepository->productExists($sku)) {
                return ["not_found" => "Product sku not found"];
            }

            // Tries to delete the product
            if ($this->productRepository->deleteProduct($sku)) {
                return ["deleted" => true];
            }
            // If the product couldn't be deleted it will return an error
            return ["not_found" => "Product could not be deleted"];
        } catch (\Throwable $ex) {
            // If an unexpected exception happened it will return a 500 = Internal Server Error
            return ["fatal_error" => "an unexpected error occurred"];
        }

    }

    /**
     *  Gets all Products from the database
     *
     * @return array The final result
     */
    public function listProducts(): array {
        try {
            // Returns the result
            return $this->productRepository->getAll();
        } catch (\Throwable $ex) {

            // In case of an error it throws a 500 = Internal Server Error
            return ["fatal_error" => "an unexpected error occurred"];
        }
    }

    public function getProduct(array $args): array {
        // Saves the value of the parameter sku in $sku
        // if the value does not exist it sets $sku to null
        $sku = $args['sku'] ?? null;

        // Checks if sku is a string not empty
        if (!is_string($sku) || trim($sku) === "") {
            // Returns an error
            return ["error" => "Invalid SKU"];
        }

        try {
            // Checks if the product doesn't exist
            if (!$this->productRepository->productExists($sku)) {
                // Returns a 404 = Not Found
                return ["not_found" => "Product not found"];
            }
            // Returns the Product
            return $this->productRepository->getProductBySku($sku);
        } catch (\Throwable $ex) {
            // In case of an error it Throws a 500 = Internal Server Error
            return ["fatal_error" => "an unexpected error occurred"];
        }
    }

}
