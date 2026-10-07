<?php

namespace App\product;

class Product {

    private int $id;

    private string $name;

    private string $sku;

    private bool $active;

    private int $categoryId;

    private string $imageUrl;

    private string $description;

    private int $price;

    private int $stock;

    /**
     * @param int $id
     * @param string $name
     * @param string $sku
     * @param bool $active
     * @param int $categoryId
     * @param string $imageUrl
     * @param string $description
     * @param int $price
     * @param int $stock
     */
    public function __construct(int $id, string $name, string $sku, bool $active, int $categoryId, string $imageUrl, string $description, int $price, int $stock)
    {
        $this->id = $id;
        $this->name = $name;
        $this->sku = $sku;
        $this->active = $active;
        $this->categoryId = $categoryId;
        $this->imageUrl = $imageUrl;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }




}
