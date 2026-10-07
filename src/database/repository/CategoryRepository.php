<?php
namespace App\database\repository;

use mysqli;
use App\category\Category;

class CategoryRepository {
    private mysqli $connection;

    public function __construct($database) {
        $this->connection = $database->getConnection();
    }

    public function getAllCategories(): array {
        $stmt = $this->connection->prepare("SELECT * FROM category");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategoryById(int $id): array {
        $stmt = $this->connection->prepare("SELECT * FROM category WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateCategory(int $id, string $name, bool $active): void {
        $stmt = $this->connection->prepare("UPDATE category SET active = ?, name = ? WHERE id = ?");
        $stmt->execute([$active, $name, $id]);

    }

    public function existsById(int $id): bool {
        $stmt = $this->connection->prepare("SELECT * FROM category WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->get_result();
        // Returns a boolean (if the result is not empty)
        return !empty($result->fetch_all(MYSQLI_ASSOC));
    }
    public function existsByName(string $name): bool {
        $stmt = $this->connection->prepare("SELECT * FROM category WHERE name = ?");
        $stmt->execute([$name]);
        $result = $stmt->get_result();
        // Returns a boolean (if the result is not empty)
        return !empty($result->fetch_all(MYSQLI_ASSOC));
    }

    public function createCategory(bool $active, string $name): Category {
        $stmt = $this->connection->prepare("INSERT INTO category (active, name) VALUES (?, ?)");
        $stmt->execute([$active, $name]);
        $id = $this->connection->insert_id;
        return new Category($id, $name, $active);
    }

    public function deleteCategory(int $id): bool {
        $stmt = $this->connection->prepare("DELETE FROM category WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->affected_rows > 0;
    }

}