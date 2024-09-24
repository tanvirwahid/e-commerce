<?php

namespace App\Actions\Orders;

use App\Actions\OrderItems\InsertOrderItemsAction;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTO\OrderData;
use App\Exceptions\NotEnoughInStockException;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class PlaceOrderAction
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private InsertOrderItemsAction $insertOrderItemsAction,
        private ProductRepositoryInterface $productRepository
    ) {}

    public function execute(OrderData $orderData)
    {
        $productIds = $this->extractProductIds($orderData->order_items);
        DB::beginTransaction();

        try {
            $idToProductMapping = $this->productRepository->lockAndGetIdToProductMapping($productIds);
            $orderData->total_amount = $this->calculateTotalAmount($orderData->order_items, $idToProductMapping);

            $order = $this->createOrder($orderData);
            $this->insertOrderItemsAction->execute($order, $orderData->order_items, $idToProductMapping);

            DB::commit();

            return $order->load('items');
        } catch (NotEnoughInStockException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function extractProductIds(array $orderItems): array
    {
        return array_map(fn ($item) => $item->product_id, $orderItems);
    }

    private function calculateTotalAmount(array $orderItems, array $idToProductMapping): float
    {
        return array_reduce($orderItems, function ($sum, $orderItem) use ($idToProductMapping) {
            return $sum + ($orderItem->quantity * $idToProductMapping[$orderItem->product_id]['price']);
        }, 0);
    }

    private function createOrder(OrderData $orderData): Order
    {
        return $this->orderRepository->create($orderData);
    }
}
