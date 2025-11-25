<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts(array $filters = []): AnonymousResourceCollection
    {
        $products = $this->productRepository->getAll($filters);
        return ProductResource::collection($products);
    }

    public function getProductById(int $id): ?ProductResource
    {
        $product = $this->productRepository->findById($id);
        return $product ? new ProductResource($product) : null;
    }

    public function createProduct(array $data): ProductResource
    {
        Log::info('Service - Creating product with data:', $data);

        // معالجة الصورة
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $data['image']->store('products', 'public');
            Log::info('Image stored at:', ['path' => $data['image']]);
        }

        $product = $this->productRepository->create($data);
        Log::info('Product created with ID:', ['id' => $product->id]);

        return new ProductResource($product);
    }

    public function updateProduct(int $id, array $data): ?ProductResource
    {
        Log::info('Service - Updating product ID: ' . $id);
        Log::info('Service - Update data:', $data);

        $product = $this->productRepository->findById($id);

        if (!$product) {
            Log::warning('Product not found in service: ' . $id);
            return null;
        }

        Log::info('Found product:', [
            'current_name' => $product->name,
            'current_price' => $product->price,
            'current_image' => $product->image
        ]);

        // معالجة الصورة إذا كانت موجودة
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            Log::info('New image provided for update');

            // حذف الصورة القديمة إذا كانت موجودة
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
                Log::info('Old image deleted: ' . $product->image);
            }

            $data['image'] = $data['image']->store('products', 'public');
            Log::info('New image stored: ' . $data['image']);
        } else {
            Log::info('No new image provided, keeping current image');
            // احتفظ بالصورة الحالية إذا مافيش صورة جديدة
            unset($data['image']);
        }

        Log::info('Final data for update:', $data);

        // التأكد من تحديث المنتج
        $updated = $this->productRepository->update($product, $data);

        if ($updated) {
            Log::info('Product updated successfully in repository');
            $product->refresh(); // جلب أحدث بيانات من الداتابيز
            Log::info('Refreshed product data:', [
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image
            ]);
        } else {
            Log::error('Failed to update product in repository');
        }

        return new ProductResource($product);
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            return false;
        }

        // حذف الصورة إذا كانت موجودة
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        return $this->productRepository->delete($product);
    }
}
