<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\OrderRequest;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Retrieve a paginated list of orders based on the provided request parameters.
     *
     * @param OrderRequest $request.
     * @return JsonResponse
     */
    public function index(OrderRequest $request): JsonResponse
    {
        $orders = Order::with('items.product', 'address', 'payment_method');

        if ($request->has('order_id')) {
            $orders->where('id', $request->order_id);
        }

        if ($request->has('status')) {
            $orders->where('status', $request->status);
        }

        if ($request->has('sort')) {
            /** @var string $sortQueryParam */
            $sortQueryParam = $request->sort;
            $parts = explode('_', $sortQueryParam);
            $sortingOrder = array_pop($parts);
            $sortingColumn = implode('_', $parts);

            $orders->orderBy($sortingColumn, $sortingOrder);
        }

        $ordersPerPage = 1;

        /** @var LengthAwarePaginator<Order> $paginatedOrders */
        $paginatedOrders = $orders->paginate($ordersPerPage);

        $paginatedOrders->getCollection()->transform(function ($order) {
            foreach ($order->items as $item) {
                /** @phpstan-ignore-next-line */
                $item->total = $item->quantity * $item->price;
            }
            return $order;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Orders retrieved successfully',
            'data' => $paginatedOrders,
        ]);
    }
}
