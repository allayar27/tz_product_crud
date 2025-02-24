<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection($this->categoryService->getAll());
    }


    public function store(CategoryRequest $request)
    {
        try {
            $result = $this->categoryService->create($request);
            return $this->success('category created successfully',
            $result,
            201
            );
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function show(string $id): CategoryResource
    {
        return new CategoryResource($this->categoryService->getById($id));
    }

    public function update(CategoryUpdateRequest $request, int $id)
    {
        try {
            $result = $this->categoryService->update($id, $request);
            return $this->success(
                'category updated successfully',
                $result
            );
        } catch (ModelNotFoundException $e) {
            return $this->error('Category not found', '', 404);
        } catch (\Exception $e) {
            return $this->error('Something went wrong: ' . $e->getMessage(), '',  500);
        }
    }

    public function destroy(string $id)
    {
        return $this->success('successfully deleted', $this->categoryService->delete($id));
    }
}
