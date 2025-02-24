<?php 

namespace App\Services\Elasticsearch;

use Elastic\Elasticsearch\Client;

class ProductIndexService
{
    public function __construct(private Client $elasticsearch) {}

    public function createIndex()
    {
        $params = [
            'index' => 'products',
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 1,
                ],
                'mappings' => [
                    'properties' => [
                        'title' => ['type' => 'text'],
                        'description' => ['type' => 'text'],
                        'price' => ['type' => 'double'],
                    ],
                ],
            ],
        ];

        return $this->elasticsearch->indices()->create($params);
    }

    public function deleteIndex()
    {
        if ($this->elasticsearch->indices()->exists(['index' => 'products'])) {
            $this->elasticsearch->indices()->delete(['index' => 'products']);
        }
    }
}
