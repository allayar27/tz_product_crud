<?php 

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAll(): Collection
    {
        return $this->categoryRepository->getAll();
    }

    public function getById(int $id)
    {
        $category = $this->categoryRepository->findById($id);
        if (!$category) {
            throw new ModelNotFoundException('Category not found');
        }
        return $category;
    }

    public function create(CategoryRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $this->saveImage($request->file('image'));
            }
            return $this->categoryRepository->create($data);
        });
    }

    public function update(int $id, CategoryUpdateRequest $request)
    {
        return DB::transaction(function () use ($id, $request) {
            $category = $this->categoryRepository->findById($id);
            if (!$category) {
                throw new ModelNotFoundException('Category not found');
            }

            $data = $request->validated();

            if ($request->hasFile('image')) {
                if ($category->image) {
                    Storage::delete($category->image);
                }
                $data['image'] = $this->saveImage($request->file('image'));
            }

            return $this->categoryRepository->update($id, $data);
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $category = $this->categoryRepository->findById($id);
            if ($category && $category->image) {
                Storage::delete($category->image);
            }

            return $this->categoryRepository->delete($id);
        });
    }

    private function saveImage(UploadedFile $image): string
    {
        return $image->store('categories', 'public');
    }
}
