<?php

namespace App\Console\Commands;

use App\Services\Elasticsearch\ProductIndexService;
use Illuminate\Console\Command;

class ElasticsearchManageIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:manage {action}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage Elasticsearch indexes';

    /**
     * Execute the console command.
     */
    public function handle(ProductIndexService $productIndex)
    {
        $action = $this->argument('action');

        match ($action) {
            'create' => $this->createIndexes($productIndex),
            'delete' => $this->deleteIndexes($productIndex),
            default => $this->error('Invalid action: use create or delete')
        };
    }

    private function createIndexes($productIndex)
    {
        $productIndex->createIndex();
        $this->info('Indexes created successfully');
    }

    private function deleteIndexes($productIndex)
    {
        $productIndex->deleteIndex();
        $this->info('Indexes deleted successfully');
    }
}
