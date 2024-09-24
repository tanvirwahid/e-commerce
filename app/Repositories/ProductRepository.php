<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTO\ProductData;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(private Product $product)
    {
    }

    public function index()
    {
        return $this->product->paginate(10);
    }

    public function create(ProductData $productData): Product
    {
        return $this->product->create([
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

    public function lockAndGetIdToProductMapping(array $productIds): array
    {
        return Product::lockForUpdate()
            ->whereIn('id', $productIds)
            ->get()
            ->mapWithKeys(function ($product) {
                return [$product->id => $product];
            })->toArray();
    }

    public function decreaseStock(array $stockData, array $productIds)
    {
        $caseStatements = '';

        foreach ($stockData as $key => $value)
        {
            $caseStatements .= "WHEN id = {$key} THEN stock - {$value} ";
        }

        $productIdsString = implode(',', $productIds);

        DB::statement("
            UPDATE products
            SET stock = CASE
                $caseStatements
                END
            WHERE id IN ($productIdsString)
        ");
    }
}
