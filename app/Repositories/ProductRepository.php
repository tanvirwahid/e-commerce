<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTO\ProductData;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function index()
    {
        return Product::paginate(10);
    }

    public function create(ProductData $productData): Product
    {
        return Product::create([
            'name' => $productData->name,
            'price' => $productData->price,
            'stock' => $productData->stock
        ]);
    }

    public function update(Product $product, ProductData $productData): Product
    {
        $product->name = $productData->name;
        $product->price = $productData->price;
        $product->stock = $productData->stock;

        $product->save();

        return $product;
    }

    public function delete(Product $product)
    {
        $product->delete();
    }
}
