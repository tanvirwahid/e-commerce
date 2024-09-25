<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTO\ProductData;
use App\Entities\Products\ProductIds;
use App\Entities\Products\ProductPositions;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(private Product $product) {}

    public function index()
    {
        return $this->product->latest()->paginate(10);
    }

    public function create(ProductData $productData): Product
    {
        return $this->product->create([
            'name' => $productData->name,
            'price' => $productData->price,
            'stock' => $productData->stock,
            'created_by' => $productData->created_by
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

    public function lockAndGetIdToProductMapping(ProductIds $productIds): array
    {
        return Product::lockForUpdate()
            ->whereIn('id', $productIds->getProductIds())
            ->get()
            ->mapWithKeys(function ($product) {
                return [$product->id => $product];
            })->toArray();
    }

    public function decreaseStock(array $stockData, array $productIds)
    {
        $caseStatements = '';

        foreach ($stockData as $key => $value) {
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

    public function getBulkPositions(ProductIds $productIds): ProductPositions
    {
        return new ProductPositions(
            Product::from('products as p1')
                ->leftJoin('products as p2', 'p2.id', '>=', 'p1.id')
                ->select('p1.id', DB::raw('COUNT(p2.id) as count'))
                ->whereIn('p1.id', $productIds->getProductIds())
                ->groupBy('p1.id')
                ->get()
                ->pluck('count')
                ->toArray()
        );
    }

    public function getPositionFromId(int $id): int
    {
        return Product::where('id', '>=', $id)
            ->count();
    }
}
