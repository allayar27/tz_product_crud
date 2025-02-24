<?php

namespace App\Observers;

use App\Models\Product;
use Elastic\Elasticsearch\Client;

class ProductObserver
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function created(Product $product): void
    {
        $this->indexProduct($product);
    }


    public function updated(Product $product): void
    {
        $this->indexProduct($product);
    }


    public function deleted(Product $product): void
    {
        $this->deleteProductIndex($product->id);
    }


    private function indexProduct(Product $product): void
    {
        $this->client->index([
            'index' => 'products',
            'id' => $product->id,
            'body' => [
                'title' => $product->title,
                'description' => $product->description,
                'price' => $product->price,
                'created_at' => $product->created_at->toDateTimeString(),
            ]
        ]);
    }

    private function deleteProductIndex(int $id): void
    {
        if ($this->client->exists(['index' => 'products', 'id' => $id])->asBool()) {
            $this->client->delete(['index' => 'products', 'id' => $id]);
        }
    }
}
