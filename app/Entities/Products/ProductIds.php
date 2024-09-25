<?php

namespace App\Entities\Products;

class ProductIds
{
    private array $productIds;

    public function getProductIds(): array
    {
        return $this->productIds;
    }

    public function setProductIds(array $productIds): ProductIds
    {
        $this->productIds = $productIds;
        return $this;
    }

}
