<?php

namespace App\Actions\Orders;

use App\Contracts\Repositories\OrderItemRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTO\OrderData;
use App\Exceptions\NotEnoughInStockException;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlaceOrderAction
{
    public function __construct(
        private OrderRepositoryInterface   $orderRepository,
        private ProductRepositoryInterface $productRepository,
        private OrderItemRepositoryInterface $orderItemRepository
    )
    {
    }

    public function execute(OrderData $orderData)
    {
        $productIds = array_map(function ($orderItem) {
            return $orderItem->product_id;
        }, $orderData->order_items);

        $newStocks = [];
        $orderItemsToCreate = [];

        DB::beginTransaction();

        try {
            $products = $this->productRepository->lockAndGetIdToProductMapping($productIds);
            $orderData->total_amount = $this->getTotalAmount($orderData->order_items, $products);

            $order = $this->orderRepository->create($orderData);

            foreach ($orderData->order_items as $orderItem) {
                $product = $products[$orderItem->product_id];

                if ($product['stock'] < $orderItem->quantity) {
                    throw new NotEnoughInStockException(
                        $product['name'] . ' does not have enough in stock',
                        JsonResponse::HTTP_UNPROCESSABLE_ENTITY
                    );
                }

                $newStocks[$orderItem->product_id] = $orderItem->quantity;

                $orderItemsToCreate[] = [
                    'order_id' => $order->id,
                    'product_id' => $orderItem->product_id,
                    'quantity' => $orderItem->quantity,
                    'price' => $products[$orderItem->product_id]['price'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $this->orderItemRepository->bulkInsert($orderItemsToCreate);
            $this->productRepository->decreaseStock($newStocks, $productIds);

            DB::commit();

            return $order->load('items');
        } catch (NotEnoughInStockException $exception)
        {
            DB::rollBack();
            throw $exception;
        } catch (\Exception $exception)
        {
            DB::rollBack();
            throw $exception;
        }
    }

    private function getTotalAmount(array $orderItems, array $products): float
    {
        $sum = 0;

        foreach ($orderItems as $orderItem) {
            $sum += ($orderItem->quantity * $products[$orderItem->product_id]['price']);
        }

        return $sum;
    }
}
