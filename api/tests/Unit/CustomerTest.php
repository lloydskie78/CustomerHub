<?php

namespace Tests\Unit;

use App\Models\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_customer()
    {
        $customerData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'contact_number' => '1234567890'
        ];

        $customer = Customer::create($customerData);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals($customerData['first_name'], $customer->first_name);
        $this->assertEquals($customerData['last_name'], $customer->last_name);
        $this->assertEquals($customerData['email'], $customer->email);
        $this->assertEquals($customerData['contact_number'], $customer->contact_number);
    }

    public function test_customer_validation_rules()
    {
        $rules = Customer::rules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('first_name', $rules);
        $this->assertArrayHasKey('last_name', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('contact_number', $rules);
    }

    public function test_customer_fillable_attributes()
    {
        $customer = new Customer();
        $fillable = $customer->getFillable();

        $this->assertIsArray($fillable);
        $this->assertContains('first_name', $fillable);
        $this->assertContains('last_name', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('contact_number', $fillable);
    }
} 