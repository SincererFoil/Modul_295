<?php

namespace App\product;


class Product {

    private int|null $id;

    private string $name;

    private string $sku;

    private int $active;

    private int|null $categoryId;

    private string $imageUrl;

    private string $description;

    private float $price;

    private int $stock;

    /**
     *  This is a Product Object,
     *  It Represents the Product from the Database
     *
     * @param int|null $id The unique ID of the product
     * @param string $name the Name of the product
     * @param string $sku the unique identifier sku of the product
     * @param int $active the active (1 or 0) of the product
     * @param int|null $categoryId the category id where the product is located
     * @param string $imageUrl an image of the product as a url
     * @param string $description a text to describe the product
     * @param float $price a price of the product
     * @param int $stock the stock amount of the product
     */
    public function __construct(int|null $id, string $name, string $sku, int $active, int|null $categoryId, string $imageUrl, string $description, float $price, int $stock)
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
     * @return int|null the id
     */
    public function getId(): int|null
    {
        return $this->id;
    }

    /**
     * @param int|null $id the id
     */
    public function setId(int|null $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string the name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name the name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string the unique sku
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku the unique sku
     */
    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return int the active value
     */
    public function isActive(): int
    {
        return $this->active;
    }

    /**
     * @param int $active active value
     */
    public function setActive(int $active): void
    {
        $this->active = $active;
    }

    /**
     * @return int|null the category id
     */
    public function getCategoryId(): int|null
    {
        return $this->categoryId;
    }

    /**
     * @param int|null $categoryId the category id
     */
    public function setCategoryId(int|null $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return string the image url
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl the image url
     */
    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return string the description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description the description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return float the description
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price the description
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int the stock amount
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock the stock amount
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }




}
