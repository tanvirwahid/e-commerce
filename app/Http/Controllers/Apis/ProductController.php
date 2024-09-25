<?php

namespace App\Http\Controllers\Apis;

use App\Actions\Products\CreateProductAction;
use App\Actions\Products\UpdateProductAction;
use App\Contracts\ListProductActionFactoryInterface;
use App\DTO\ProductData;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController
{
    public function index(
        ListProductActionFactoryInterface $listProductActionFactory,
    ) {
        return response()->json([
            'products' => $listProductActionFactory->getListProductAction()->execute(),
            'message' => 'SUccessfully fetched',
        ]);
    }

    public function store(CreateProductRequest $request, CreateProductAction $createProductAction)
    {
        return response()->json([
            'product' => $createProductAction->execute(
                ProductData::fromRequest($request)
            ),
            'message' => 'Successfully created',
        ], JsonResponse::HTTP_CREATED);
    }

    public function update(
        UpdateProductRequest $request,
        Product $product,
        UpdateProductAction $updateProductAction
    ) {
        return response()->json([
            'product' => $updateProductAction->execute(
                $product,
                ProductData::fromRequest($request)
            ),
            'message' => 'Successfully updated',
        ]);
    }

}
