<?php

namespace App\Entities\Products;

class ProductPositions
{
    /**
     * @param array $positions
     */
    public function __construct(private array $positions) {}

    public function getPositions(): array
    {
        return $this->positions;
    }
}
