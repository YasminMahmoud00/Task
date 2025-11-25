<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private OrderRepository $orderRepository)
    {
    }

    public function getMyOrders(int $userId): AnonymousResourceCollection
    {
        $orders = $this->orderRepository->getMyOrders($userId);
        return OrderResource::collection($orders);
    }

    public function createOrder(array $data, int $userId): OrderResource
    {
        return DB::transaction(function () use ($data, $userId) {
            // حساب السعر الإجمالي
            $products = Product::whereIn('id', $data['product_ids'])->get();
            $totalPrice = $products->sum('price');

            // إنشاء الطلب
            $order = $this->orderRepository->create([
                'user_id' => $userId,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // إنشاء عناصر الطلب
            foreach ($products as $product) {
                $this->orderRepository->createOrderItem($order, [
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => 1,
                ]);
            }

            return new OrderResource($order->load('orderItems.product'));
        });
    }
}
