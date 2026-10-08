<?php
namespace App\database\repository;

use App\database\Database;
use App\product\Product;
use mysqli;

class ProductRepository {

    private mysqli $connection;

    /**
     *  Initializes the Database Connection
     * @param Database $database database Connection
     */
    public function __construct(Database $database) {
        $this->connection = $database->getConnection();
    }


    /**
     *  Selects and returns all Product from the Database
     *
     * @return array returns all Products in a array
     */
    public function getAll(): array {
        $stmt = $this->connection->prepare("SELECT * FROM product");
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
        $stmt = $this->connection->prepare("SELECT * FROM product WHERE sku = ?");
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
        $stmt = $this->connection->prepare("SELECT * FROM product WHERE sku = ?");
        $stmt->execute([$sku]);
        $result = $stmt->get_result();
        return $result->fetch_assoc();
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
            "INSERT INTO product(sku, active, id_category, name, image, description, price, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
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
     * @param Product $product the product new values
     * @return array returns a array with the new product informations
     */
    public function updateProduct(Product $product): array {
        $stmt = $this->connection->prepare(
            "UPDATE product SET  active = ?, id_category = ?,
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

        try {
            // Gets the product id from the sku
            $id = $this->getProductIdBySku($product->getSku());
        } catch (\Throwable $exception) {
            $id = null;
        }

        // Returns the New updated verion of the product
        return [
            "id" => $id,
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
        $stmt = $this->connection->prepare("DELETE FROM product WHERE sku = ?");
        $stmt->execute([$sku]);
        return $stmt->affected_rows > 0;

    }

    /**
     * Returns a product id from a prodict sku
     *
     * @param string $sku the prooducts sku
     * @return int|null the products id
     */
    public function getProductIdBySku(string $sku): int|null {
        $stmt = $this->connection->prepare("SELECT product_id FROM product WHERE sku = ?");
        $stmt->execute([$sku]);
        $result = $stmt->get_result()->fetch_assoc();

        return $result !== null ? (int) $result["product_id"] : null;

    }

    /**
     *  Checks if a product exists with a specific category id,
     *  This method is used to cancel Category deletions if they still have Products
     *
     * @param int $categoryId the category id
     * @return bool if a product exists with the category id
     */
    public function productExistWithCategoryId(int $categoryId): bool {
        $stmt = $this->connection->prepare("SELECT * FROM product WHERE id_category = ?");
        $stmt->execute([$categoryId]);
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

}