<?php

namespace App\Contracts\Repositories;

use App\DTO\ProductData;
use App\Models\Product;

interface ProductRepositoryInterface
{
    public function index();

    public function create(ProductData $productData): Product;

    public function update(Product $product, ProductData $productData): Product;

    public function delete(Product $product);

    public function lockAndGetIdToProductMapping(array $productIds): array;

    public function decreaseStock(array $stockData, array $productIds);
}
