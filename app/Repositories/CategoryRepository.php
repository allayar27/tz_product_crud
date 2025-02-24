<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getAll()
    {
        return Category::all();
    }

    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data)
    {
        $category = Category::query()->findOrFail($id);
        return  $category->update($data);
    }

    public function delete(int $id): bool
    {
        return Category::destroy($id) > 0;
    }
}