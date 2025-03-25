<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_customers()
    {
        Customer::factory()->count(3)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_customer()
    {
        $customerData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'contact_number' => '1234567890'
        ];

        $response = $this->postJson('/api/customers', $customerData);

        $response->assertStatus(201)
            ->assertJson($customerData);

        $this->assertDatabaseHas('customers', $customerData);
    }

    public function test_can_update_customer()
    {
        $customer = Customer::factory()->create();
        $updateData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'contact_number' => '0987654321'
        ];

        $response = $this->putJson("/api/customers/{$customer->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson($updateData);

        $this->assertDatabaseHas('customers', $updateData);
    }

    public function test_can_delete_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_cannot_create_customer_with_duplicate_email()
    {
        $existingCustomer = Customer::factory()->create();
        $customerData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => $existingCustomer->email,
            'contact_number' => '1234567890'
        ];

        $response = $this->postJson('/api/customers', $customerData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_can_search_customers()
    {
        Customer::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com'
        ]);

        Customer::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com'
        ]);

        $response = $this->getJson('/api/customers?search=john');

        $response->assertStatus(200)
            ->assertJsonFragment(['first_name' => 'John'])
            ->assertJsonMissing(['first_name' => 'Jane']);
    }
} 