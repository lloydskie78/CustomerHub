<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ElasticsearchService
{
    private $host;
    private $port;
    private $index = 'customers';

    public function __construct()
    {
        $this->host = env('ELASTICSEARCH_HOST', 'elasticsearch');
        $this->port = env('ELASTICSEARCH_PORT', '9200');
    }

    private function getBaseUrl()
    {
        return "http://{$this->host}:{$this->port}";
    }

    public function createIndex()
    {
        $url = "{$this->getBaseUrl()}/{$this->index}";
        
        $response = Http::put($url, [
            'mappings' => [
                'properties' => [
                    'first_name' => ['type' => 'text'],
                    'last_name' => ['type' => 'text'],
                    'email' => ['type' => 'keyword'],
                    'contact_number' => ['type' => 'keyword']
                ]
            ]
        ]);

        return $response->successful();
    }

    public function indexCustomer(Customer $customer)
    {
        $url = "{$this->getBaseUrl()}/{$this->index}/_doc/{$customer->id}";

        $response = Http::put($url, [
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'email' => $customer->email,
            'contact_number' => $customer->contact_number
        ]);

        if (!$response->successful()) {
            Log::error('Failed to index customer in Elasticsearch', [
                'customer_id' => $customer->id,
                'response' => $response->json()
            ]);
        }

        return $response->successful();
    }

    public function deleteCustomer($customerId)
    {
        $url = "{$this->getBaseUrl()}/{$this->index}/_doc/{$customerId}";
        
        $response = Http::delete($url);

        if (!$response->successful()) {
            Log::error('Failed to delete customer from Elasticsearch', [
                'customer_id' => $customerId,
                'response' => $response->json()
            ]);
        }

        return $response->successful();
    }

    public function searchCustomers($query)
    {
        $url = "{$this->getBaseUrl()}/{$this->index}/_search";

        $response = Http::post($url, [
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'multi_match' => [
                                'query' => $query,
                                'fields' => ['first_name', 'last_name']
                            ]
                        ],
                        [
                            'term' => [
                                'email' => $query
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        if ($response->successful()) {
            return collect($response->json()['hits']['hits'])->pluck('_source');
        }

        return collect();
    }
} 