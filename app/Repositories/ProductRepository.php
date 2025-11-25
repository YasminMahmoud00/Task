<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ProductRepository
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = Product::query();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        return $query->paginate(10);
    }

    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function create(array $data): Product
    {
        Log::info('Repository - Creating product:', $data);
        return Product::create($data);
    }

    public function update(Product $product, array $data): bool
    {
        Log::info('Repository - Updating product:', [
            'product_id' => $product->id,
            'data' => $data
        ]);

        try {
            $result = $product->update($data);
            Log::info('Update result: ' . ($result ? 'true' : 'false'));

            if ($result) {
                Log::info('Product updated in database:', [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Repository update error: ' . $e->getMessage());
            return false;
        }
    }

    public function delete(Product $product): bool
    {
        Log::info('Repository - Deleting product:', ['id' => $product->id]);
        return $product->delete();
    }
}
