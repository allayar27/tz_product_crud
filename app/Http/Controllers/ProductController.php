<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection($this->productService->getAll());
    }

    public function store(ProductRequest $request)
    {
        try {
            $result = $this->productService->create($request);
            return $this->success('product created successfully',
            $result,
            201,
            );
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function show(int $id): ProductResource
    {
        return new ProductResource($this->productService->getById($id));
    }


    public function update(ProductUpdateRequest $request, int $id)
    {
        try {
            $result = $this->productService->update($id, $request);
            return $this->success('product updated successfully',$result);

        } catch (ModelNotFoundException $e) {
            return $this->error('Product not found', '',  404);
        } catch (\Exception $e) {
            return $this->error('Something went wrong: ' . $e->getMessage(), '', 500);
        }
    }


    public function destroy(string $id)
    {
        return $this->success('successfully deleted', $this->productService->delete($id));
    }
}
