<?php
namespace App\service;

use App\database\repository\CategoryRepository;
use Psr\Http\Message\ResponseInterface;

class CategoryService {

    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function createCategory($request): array {

        // Parses Input body
        $array = $request->getParsedBody();

        // Checks if active and name is set.
        if (!isset($array['active'], $array['name'])) {
            return ["error" => "Missing required parameter 'active' or 'name'"];
        }

        // Checks if active is a boolean and if the name is a string
        if (!is_bool($array['active']) && !is_string($array['name'])) {
            return ["error" => "Wrong parameter type 'active' or 'name'"];
        }

        // Checks if the name is greater than 500 letters
        if ($array['name'] >= 500) {
            return ["error" => "Category name is too long"];
        }

        try {
            if ($this->categoryRepository->existsByName($array['name'])) {
                return ["error" => "Category already exists"];
            }
            $category = $this->categoryRepository->createCategory($array['active'], $array['name']);
            return ["id" => $category->getId(), "name" => $category->getName(), "active" => $category->isActive()];
        } catch (\Throwable $exception) {
            return ["fatal error" => "An unexpected error occurred debug" . $exception->getMessage()];
        }



    }

    public function getAllCategories(): array {
        try {
            $categories = $this->categoryRepository->getAllCategories();
            return $categories;
        } catch (Exception $e) {
            return ["fatal error" => $e->getMessage()];
        }
    }

    public function updateCategory($request): array {
        $input = $request->getParsedBody();
        return $this->categoryRepository->updateCategory($input);
    }


}
