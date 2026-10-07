<?php
namespace App\database\repository;

use App\category\Category;
use App\product\Product;
use mysqli;

class ProductRepository {

    private mysqli $connection;

    /**
     *  Initializes the Database Connection
     * @param $connection database Connection
     */
    public function __construct($connection) {
        $this->connection = $connection;
    }


    /**
     *  Selects and returns all Product from the Database
     *
     * @return array returns all Products in a array
     */
    public function getAll(): array {
        $stmt = $this->connection->prepare("SELECT * FROM products");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     *
     * Checks if a product with a specific SKU exists in the Database
     *
     * @param string $sku product's sku key
     * @return bool returns true if the product exists
     *              returns false if the product dosn't exists
     */
    public function productExists(string $sku): bool {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE sku = ?");
        $stmt->execute([$sku]);
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }


    /**
     * Selects and returns a Product from the Database
     *
     * @param string $sku product's SKU
     * @return array the final Select result with the data
     */
    public function getProductBySku(string $sku) : array {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE sku = ?");
        $stmt->execute([$sku]);
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    /**
     *  Creates a new Product in the Database
     *
     * @param Product $product The preset for the product
     * @return array Returns the created product
     */
    public function createProduct(Product $product): array
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO products
        (sku, active, id_category, name, image, description, price, stock)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $product->getSku(),
            $product->isActive(),
            $product->getCategoryId(),
            $product->getName(),
            $product->getImageUrl(),
            $product->getDescription(),
            $product->getPrice(),
            $product->getStock()
        ]);

        return [
            "id" => $this->connection->insert_id,
            "sku" => $product->getSku(),
            "active" => $product->isActive(),
            "id_category" => $product->getCategoryId(),
            "name" => $product->getName(),
            "image" => $product->getImageUrl(),
            "description" => $product->getDescription(),
            "price" => $product->getPrice(),
            "stock" => $product->getStock()
        ];
    }


    /**
     *  Updates an existing product in the Database
     *
     * @param Product $product the products new values
     * @return array returns a array with the new product informations
     */
    public function updateProduct(Product $product): array {
        $stmt = $this->connection->prepare(
            "UPDATE products SET  active = ?, id_category = ?,
                name = ?, image = ?, description = ?, price = ?, stock = ? WHERE sku = ?");

        $stmt->execute([
            $product->isActive(),
            $product->getCategoryId(),
            $product->getName(),
            $product->getImageUrl(),
            $product->getDescription(),
            $product->getPrice(),
            $product->getStock(),
            $product->getSku()
        ]);

        return [
            "id" => null,
            "sku" => $product->getSku(),
            "active" => $product->isActive(),
            "id_category" => $product->getCategoryId(),
            "name" => $product->getName(),
            "image" => $product->getImageUrl(),
            "description" => $product->getDescription(),
            "price" => $product->getPrice(),
            "stock" => $product->getStock()
        ];
    }


    /**
     *  Delete's a Product from the Database
     *
     * @param string $sku product's SKU
     * @return bool returns true if the statement was successful
     */
    public function deleteProduct(string $sku): bool {
        $stmt = $this->connection->prepare("DELETE FROM products WHERE sku = ?");
        $stmt->execute([$sku]);
        return $stmt->affected_rows > 0;

    }

}