<?php 

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Repositories\ProductRepository;
use Elastic\Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected ProductRepository $productRepository;
    protected Client $client;

    public function __construct(ProductRepository $productRepository, Client $client)
    {
        $this->productRepository = $productRepository;
        $this->client = $client;
    }

    public function getAll(): Collection
    {
        return $this->productRepository->getAll();
    }

    public function getById(int $id)
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            throw new ModelNotFoundException('Product not found');
        }
        return $product;
    }

    public function create(ProductRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $data['image'] = $this->saveImage($request->file('image'));
            }
            $product = $this->productRepository->create($data);
            return $product;
        });
    }

    public function update(int $id, ProductUpdateRequest $request)
    {
        return DB::transaction(function () use ($id, $request) {
            $product = $this->productRepository->findById($id);
            if (!$product) {
                throw new ModelNotFoundException('Product not found');
            }
            
            $data = $request->validated();
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::delete($product->image);
                }
                $data['image'] = $this->saveImage($request->file('image'));
            }
            return $this->productRepository->update($id, $data);
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $product = $this->productRepository->findById($id);
            if ($product && $product->image) {
                Storage::delete($product->image);
            }

            $deleted = $this->productRepository->delete($id);

            return $deleted;
        });
    }

    private function saveImage(UploadedFile $image): string
    {
        return $image->store('products', 'public');
    }

}
