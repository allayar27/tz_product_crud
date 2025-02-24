<?php 

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getAll()
    {
        return Product::all();
    }

    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(int $id, array $data)
    {
        $product = Product::query()->findOrFail($id);
        return $product->update($data);
    }

    public function delete(int $id): bool
    {
        return Product::destroy($id) > 0;
    }
}
