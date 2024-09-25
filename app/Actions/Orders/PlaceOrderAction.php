<?php

namespace App\Actions\Orders;

use App\Actions\OrderItems\InsertOrderItemsAction;
use App\CacheInvalidators\ProductBulkUpdateCacheInvalidator;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTO\OrderData;
use App\Entities\Products\ProductIds;
use App\Exceptions\NotEnoughInStockException;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class PlaceOrderAction
{
    public function __construct(
        private Order                             $order,
        private OrderRepositoryInterface          $orderRepository,
        private InsertOrderItemsAction            $insertOrderItemsAction,
        private ProductRepositoryInterface        $productRepository,
        private ProductBulkUpdateCacheInvalidator $cacheInvalidator,
        private ProductIds                        $productIds
    )
    {
    }

    public function execute(OrderData $orderData)
    {
        $this->productIds->setProductIds($this->extractProductIds($orderData->order_items));
        DB::beginTransaction();

        try {
            $idToProductMapping = $this->productRepository->lockAndGetIdToProductMapping($this->productIds);
            $orderData->total_amount = $this->calculateTotalAmount($orderData->order_items, $idToProductMapping);

            $this->order = $this->createOrder($orderData);
            $this->insertOrderItemsAction->execute($this->order, $orderData->order_items, $idToProductMapping);

            DB::commit();

        } catch (NotEnoughInStockException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        $this->cacheInvalidator->invalidateCache($this->productIds);
        return $this->order->load('items');
    }

    private function extractProductIds(array $orderItems): array
    {
        return array_map(fn($item) => $item->product_id, $orderItems);
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
