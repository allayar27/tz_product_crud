<?php

namespace App\Providers;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use GuzzleHttp\Client as GuzzleClient;
use Http\Discovery\Psr18Client;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Client\ClientInterface;

class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // $this->app->singleton(ClientInterface::class, function () {
        //     return new Psr18Client(new GuzzleHttpClient());
        // });

        $this->app->singleton(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts([config('elasticsearch.host')])
                ->setHttpClient(new GuzzleClient())
                ->build();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
