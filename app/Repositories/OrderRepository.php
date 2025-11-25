<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository
{
    public function getMyOrders(int $userId): LengthAwarePaginator
    {
        return Order::with('orderItems.product')
                    ->where('user_id', $userId)
                    ->paginate(10);
    }

    public function findById(int $id): ?Order
    {
        return Order::with('orderItems.product')->find($id);
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function createOrderItem(Order $order, array $itemData): OrderItem
    {
        return $order->orderItems()->create($itemData);
    }
}
