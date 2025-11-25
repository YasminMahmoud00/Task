<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // جميع المنتجات
    public function index(): JsonResponse
    {
        $products = $this->productService->getAllProducts();
        return response()->json($products);
    }

    // منتج واحد
    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return response()->json($product);
    }

    // إضافة منتج
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request);

        return response()->json([
            'status' => 'success',
            'message' => 'Product created',
            'data' => $product
        ], 201);
    }

    // تعديل منتج
  public function update(StoreProductRequest $request, int $id): JsonResponse
{
    \Log::info('=== بداية التحديث ===');
    \Log::info('رقم المنتج اللي هيتحدث: ' . $id);
    \Log::info('البيانات الجديدة:', $request->all());

    try {
        $product = $this->productService->updateProduct($id, $request->validated());

        if (!$product) {
            \Log::warning('المنتج مش موجود: ' . $id);
            return response()->json(['message' => 'المنتج مش موجود'], 404);
        }

        \Log::info('تم تحديث المنتج بنجاح:', [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price
        ]);
        \Log::info('=== نهاية التحديث ===');

        return response()->json($product);

    } catch (\Exception $e) {
        \Log::error('خطأ في التحديث: ' . $e->getMessage());
        return response()->json(['error' => 'فشل التحديث'], 500);
    }
}
    // حذف منتج
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->productService->deleteProduct($id);

        if (!$deleted) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted successfully'
        ]);
    }
}
