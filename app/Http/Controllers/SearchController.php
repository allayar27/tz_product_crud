<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Services\Elasticsearch\ProductSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected ProductSearchService $searchService;

    public function __construct(ProductSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $query = $request->input('query');

        $results = $this->searchService->search($query);

        return $this->response($results);
    }
}
