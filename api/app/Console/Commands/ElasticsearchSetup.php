<?php

namespace App\Console\Commands;

use App\Services\ElasticsearchService;
use Illuminate\Console\Command;

class ElasticsearchSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up Elasticsearch indices and mappings';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ElasticsearchService $elasticsearch)
    {
        $this->info('Setting up Elasticsearch...');

        try {
            $elasticsearch->createIndex();
            $this->info('Elasticsearch setup completed successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to set up Elasticsearch: ' . $e->getMessage());
            return 1;
        }
    }
} 