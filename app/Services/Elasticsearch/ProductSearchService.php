<?php 

namespace App\Services\Elasticsearch;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Exception;

class ProductSearchService
{
    protected Client $client;
    protected string $index = 'products';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function search(string $query): array
    {
        try {
            $response = $this->client->search([
                'index' => $this->index,
                'body'  => [
                    'query' => [
                        'multi_match' => [
                            'query'  => $query,
                            'fields' => ['title^2', 'description'],
                        ]
                    ]
                ]
            ]);

            return $response['hits']['hits'] ?? [];
        } catch (ClientResponseException | ServerResponseException | Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
