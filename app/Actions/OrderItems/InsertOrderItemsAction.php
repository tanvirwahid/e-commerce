<?php

namespace App\Actions\OrderItems;

use App\Contracts\Repositories\OrderItemRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTO\OrderItemData;
use App\Exceptions\NotEnoughInStockException;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class InsertOrderItemsAction
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private OrderItemRepositoryInterface $orderItemRepository
    ) {}

    public function execute(
        Order $order,
        /** @var OrderItemData[] */
        array $orderItems,
        array $idToProductMapping
    ) {
        $stockChange = [];
        $orderItemsToCreate = [];

        foreach ($orderItems as $orderItem) {
            $this->validateStock($orderItem, $idToProductMapping);

            $stockChange[$orderItem->product_id] = $orderItem->quantity;
            $orderItemsToCreate[] = $this->mapOrderItem($order->id, $orderItem, $idToProductMapping);
        }

        $this->orderItemRepository->bulkInsert($orderItemsToCreate);
        $this->productRepository->decreaseStock($stockChange, array_keys($stockChange));
    }

    private function validateStock($orderItem, array $idToProductMapping)
    {
        $product = $idToProductMapping[$orderItem->product_id];

        if ($product['stock'] < $orderItem->quantity) {
            throw new NotEnoughInStockException(
                "{$product['name']} does not have enough in stock",
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    private function mapOrderItem(int $orderId, $orderItem, array $idToProductMapping): array
    {
        return [
            'order_id' => $orderId,
            'product_id' => $orderItem->product_id,
            'quantity' => $orderItem->quantity,
            'price' => $idToProductMapping[$orderItem->product_id]['price'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
