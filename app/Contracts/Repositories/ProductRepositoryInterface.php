<?php

namespace App\Contracts\Repositories;

use App\DTO\ProductData;
use App\Entities\Products\ProductIds;
use App\Entities\Products\ProductPositions;
use App\Models\Product;

interface ProductRepositoryInterface
{
    public function index();

    public function create(ProductData $productData): Product;

    public function update(Product $product, ProductData $productData): Product;

    public function lockAndGetIdToProductMapping(ProductIds $productIds): array;

    public function decreaseStock(array $stockData, array $productIds);

    public function getBulkPositions(ProductIds $productIds): ProductPositions;

    public function getPositionFromId(int $id): int;
}
