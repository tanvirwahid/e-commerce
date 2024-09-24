<?php

namespace App\Http\Controllers\Apis;

use App\Actions\Orders\ListAuthenticatedUsersOrdersAction;
use App\Actions\Orders\PlaceOrderAction;
use App\DTO\OrderData;
use App\Exceptions\NotEnoughInStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCreationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(ListAuthenticatedUsersOrdersAction $action)
    {
        return response()->json([
            'orders' => $action->execute(),
            'message' => 'Successfully fetched'
        ]);
    }

    public function store(OrderCreationRequest $request, PlaceOrderAction $orderAction)
    {
        try {
            return response()->json([
                'order' => $orderAction->execute(
                    OrderData::fromRequest($request)
                ),
                'message' => 'Successfully ordered'
            ]);
        } catch (NotEnoughInStockException $exception) {
            return response()->json([
                'data' => [],
                'message' => $exception->getMessage()
            ], $exception->getStatusCode());
        } catch (\Exception $exception) {
            Log::error($exception);

            return response()->json([
                'data' => [],
                'message' => $exception->getMessage()
            ], $exception->status ?? JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
