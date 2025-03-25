<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\ElasticsearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    private $elasticsearchService;

    public function __construct(ElasticsearchService $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
    }

    /**
     * Display a listing of customers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('search')) {
            return response()->json(
                $this->elasticsearchService->searchCustomers($request->search)
            );
        }

        return response()->json(Customer::all());
    }

    /**
     * Store a newly created customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Customer::rules());

        try {
            DB::beginTransaction();

            $customer = Customer::create($validated);
            
            $this->elasticsearchService->indexCustomer($customer);

            DB::commit();

            return response()->json($customer, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create customer', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create customer'], 500);
        }
    }

    /**
     * Display the specified customer.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return response()->json($customer);
    }

    /**
     * Update the specified customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate(Customer::rules($customer->id));

        try {
            DB::beginTransaction();

            $customer->update($validated);
            
            $this->elasticsearchService->indexCustomer($customer);

            DB::commit();

            return response()->json($customer);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update customer', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to update customer'], 500);
        }
    }

    /**
     * Remove the specified customer.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        try {
            DB::beginTransaction();

            $customer->delete();
            
            $this->elasticsearchService->deleteCustomer($customer->id);

            DB::commit();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete customer', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to delete customer'], 500);
        }
    }
} 