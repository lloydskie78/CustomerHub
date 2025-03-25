<?php

namespace App\Providers;

use App\Services\ElasticsearchService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ElasticsearchService::class, function ($app) {
            return new ElasticsearchService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Don't try to create index during package discovery
        if (php_sapi_name() === 'cli' && isset($_SERVER['COMPOSER_BINARY'])) {
            return;
        }

        try {
            $elasticsearch = $this->app->make(ElasticsearchService::class);
            $elasticsearch->createIndex();
        } catch (\Exception $e) {
            Log::warning('Failed to create Elasticsearch index: ' . $e->getMessage());
        }
    }
} 