<?php
namespace App\service;

use App\database\repository\CategoryRepository;
use Psr\Http\Message\ServerRequestInterface;

class CategoryService {

    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     *  Validates and executes the Creation of a new Category
     *
     * @param ServerRequestInterface $request The request with body
     * @return array Returns the result of the creation in an assiociative array
     *
     */
    public function createCategory(ServerRequestInterface $request): array {

        // Parses Input body
        $array = $request->getParsedBody();

        // Checks if active and name is set.
        if (!isset($array['active'], $array['name'])) {
            return ["error" => "Missing required parameter 'active' or 'name'"];
        }

        // Checks if active is a int and if the name is a string
        if (!is_int($array['active']) || !is_string($array['name'])) {
            return ["error" => "Wrong parameter type 'active' or 'name'"];
        }

        // Sets the active value into the $active variable
        $active = $array['active'];

        // Checks if active is in between 0 and 1
        if ($active !== 0 && $active !== 1) {
            return ["error" => "Invalid value, active must be 0 or 1"];
        }

        // Checks if the name is greater than 500 letters
        if (strlen($array['name']) > 500) {
            return ["error" => "Category name is too long"];
        }

        try {
            // Checks if the Category name already exists
            if ($this->categoryRepository->existsByName($array['name'])) {
                return ["error" => "Category already exists"];
            }

            // Executes the createCategory method to create a new Category in the Database
            $category = $this->categoryRepository->createCategory($array['active'], $array['name']);
            // Returns the new created category
            return ["id" => $category->getId(), "name" => $category->getName(), "active" => $category->isActive()];
        } catch (\Throwable $exception) {
            return ["fatal_error" => "An unexpected error occurred"];
        }



    }

    /**
     *  SELECTS / Gets all Categories from the database and returns them
     *
     * @return array returns an assiociative array with the result of the request
     */
    public function getAllCategories(): array {
        try {
            $categories = $this->categoryRepository->getAllCategories();
            return $categories;
            // If anything goes wrong (Database crash, etc.) it will return a fatal error
        } catch (\Throwable $e) {
            return ["fatal_error" => "an unexpected error occurred"];
        }
    }

    public function updateCategory(ServerRequestInterface $request, array $args): array {
        // Sets the id to the value of category_id or null
        $id = $args['category_id'] ?? null;
        // Sets the name to "name" from the body or else null
        $name = $request->getParsedBody()["name"] ?? null;
        // Sets the active variable to the given "active" parameter from the body.
        // If the key "active" is not set it will return null instead of "Undefined array key"
        $active = $request->getParsedBody()["active"] ?? null;

        // Checks if all parameters are set
        if (!(isset($id) && isset($name) && isset($active))) {
            return ["error" => "Missing required parameter 'id',  'name' or 'active'"];
        }

        // Checks if the parameter matches the required datatypes from the database
        if (!(is_numeric($id) && is_string($name) && is_int($active))) {
            return ["error" => "Wrong parameter type 'id' or 'name' or 'active'"];
        }

        // Converts the category_id path parameter from a string to an integer
        $id = (int) $id;

        // Checks if active is in between 0 - 1
        if ($active !== 0 && $active !== 1) {
            return ["error" => "Invalid value, active must be 0 or 1"];
        }

        if (strlen($name) > 500) {
            return ["error" => "Category name is too long"];
        }

        try {
            // Checks if the category exists by id
            if (!$this->categoryRepository->existsById($id)) {
                return ["not_found" => "Category does not exist"];
            }

            // Checks if the category exists by name
            if ($this->categoryRepository->existsByNameExceptId($name, $id)) {
                return ["error" => "Category name already exists"];
            }
            // Executes the update
            $result = $this->categoryRepository->updateCategory($id, $name, $active);
        } catch (\Throwable $exception) {
            return ["fatal_error" => "an unexpected error occurred "];
        }
        // Returns the final result
        return $result;
    }


    /**
     *  Gets a specific category from the Database
     *
     * @param array $args The argumments given by the path-variable {category_id}))
     * @return array Returns the final result in a array
     */
    public function getCategory(array $args): array {
        // Checks if the "category_id" value is set else null
        $id = $args['category_id'] ?? null;
        // Checks if the $id is set
        if (!isset($id)) {
            return ["error" => "Missing required parameter 'category_id'"];
        }

        // Checks if the $id is a number
        if (!is_numeric($id)) {
            return ["error" => "Wrong parameter type 'category_id'"];
        }

        // Converts the category_id path parameter from a string to an integer
        $id = (int) $id;

        try {
            // Checks if a Category with the id exists
            if (!$this->categoryRepository->existsById($id)) {
                return ["not_found" => "Category does not exist"];
            }
            // Returns the Category
            return $this->categoryRepository->getCategoryById($id);
        } catch (\Throwable $exception) {
            // In case of an error it throws a 500 - internal server error
            return ["fatal_error" => "an unexpected error occurred"];
        }
    }


    /**
     *  Deletes a specific category from the Database
     *
     * @param array $args The argumments given by the path-variable {category_id}))
     * @return array|null Returns the errors in a array or null for a successful deletion
     */
    public function deleteCategory(array $args): array|null {
        // Checks if the "category_id" value is set else null
        $id = $args['category_id'] ?? null;
        // Checks if the $id is set
        if (!isset($id)) {
            return ["error" => "Missing required parameter 'category_id'"];
        }

        // Checks if the $id is a number
        if (!is_numeric($id)) {
            return ["error" => "Wrong parameter type 'category_id'"];
        }

        // Converts the category_id path parameter from a string to an integer
        $id = (int) $id;

        try {
            // Checks if a Category with the id exists
            if (!$this->categoryRepository->existsById($id)) {
                return ["not_found" => "Category does not exist"];
            }
            $this->categoryRepository->deleteCategory($id);
            return null;
        } catch (\Throwable $exception) {
            // In case of an error it throws a 500 - internal server error
            return ["fatal_error" => "an unexpected error occurred"];
        }
    }


}
