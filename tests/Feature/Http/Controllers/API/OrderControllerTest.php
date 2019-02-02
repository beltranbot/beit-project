<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_create_a_customer()
    {
        $customer = $this->create('Customer');

        $this->assertDatabaseHas('customer',[
            'customer_id' => $customer['customer_id'],
            'name' => $customer['name'],
            'email' => $customer['email'],
        ]);
    }

    /**
     * @test
     */
    public function can_create_a_product()
    {
        $product = $this->create('Product');

        $this->assertDatabaseHas('product',[
            'product_id' => $product['product_id'],
            'name' => $product['name'],
            'product_description' => $product['product_description'],
            'price' => $product['price'],
        ]);
    }

    /**
     * @test
     */
    public function can_link_customer_with_products()
    {
        $customer = $this->create('Customer', [], true);
        $product1 = $this->create('Product');
        $product2 = $this->create('Product');
        $product3 = $this->create('Product');

        $customer->products()->attach([
            $product1['product_id'],
            $product2['product_id'],
            $product3['product_id'],
        ]);

        $i = 0;

        foreach ($customer->products as $product) {
            if ($i++ === 0) {
                $this->assertDatabaseHas('customer_product',[
                    'customer_id' => $customer->customer_id,
                    'product_id' => $product1['product_id'],
                ]);
            } else if ($i++ === 1){
                $this->assertDatabaseHas('customer_product',[
                    'customer_id' => $customer->customer_id,
                    'product_id' => $product2['product_id'],
                ]);
            } else if ($i++ === 2) {
                $this->assertDatabaseHas('customer_product',[
                    'customer_id' => $customer->customer_id,
                    'product_id' => $product3['product_id'],
                ]);
            }
        }
    }

    /**
     * @test
     */
    public function will_fail_with_validation_errors_when_creating_an_order_with_wrong_inputs()
    {
        $faker = Factory::create();

        $customer1 = $this->create('Customer');

        $product1 = $this->create('Product');
        $product2 = $this->create('Product');
        $product3 = $this->create('Product');
        $product4 = $this->create('Product');
        $product5 = $this->create('Product');
        $product6 = $this->create('Product');
        $product7 = $this->create('Product');

        $customer1->products()->attach([
            $product1['product_id'],
            $product2['product_id'],
            $product3['product_id'],
            $product4['product_id'],
            $product5['product_id'],
            $product7['product_id'],
        ]);

        // invalid customer id - null
        $response = $this->json('POST', '/api/orders', [
            // 'customer_id' => $customer1['customer_id'],
            'customer_id' => null,
            'creation_date' => date("Y-m-d"),
            'delivery_address' => $faker->address,
            'order_details' => [
                [
                    'quantity' => random_int(1, 100),
                    'product_id' => $product1['product_id']
                ]
            ],
        ]);        
        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'customer_id' => ['The customer id field is required.'],
                    'order_details.0.product_id' => ['The selected order_details.0.product_id is invalid.']
                ]
            ]);
        
        // invalid customer id - -1
        $response = $this->json('POST', '/api/orders', [
            // 'customer_id' => $customer1['customer_id'],
            'customer_id' => -1,
            'creation_date' => date("Y-m-d"),
            'delivery_address' => $faker->address,
            'order_details' => [
                [
                    'quantity' => random_int(1, 100),
                    'product_id' => $product1['product_id']
                ]
            ],
        ]);

        
        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'customer_id' => ['The selected customer id is invalid.'],
                    'order_details.0.product_id' => ['The selected order_details.0.product_id is invalid.']
                ]
            ]);
        
        // invalid customer id - 1000
        $response = $this->json('POST', '/api/orders', [
            // 'customer_id' => $customer1['customer_id'],
            'customer_id' => 1000,
            'creation_date' => date("Y-m-d"),
            'delivery_address' => $faker->address,
            'order_details' => [
                [
                    'quantity' => random_int(1, 100),
                    'product_id' => $product1['product_id']
                ]
            ],
        ]);

        
        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'customer_id' => ['The selected customer id is invalid.'],
                    'order_details.0.product_id' => ['The selected order_details.0.product_id is invalid.']
                ]
            ]);
        

        // invalid customer date - 1000
        $response = $this->json('POST', '/api/orders', [
            'customer_id' => $customer1['customer_id'],
            'creation_date' => null,
            'delivery_address' => $faker->address,
            'order_details' => [
                [
                    'quantity' => random_int(1, 100),
                    'product_id' => $product1['product_id']
                ]
            ],
        ]);

        
        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'creation_date' => ['The creation date field is required.']
                ]
            ]);
        
        // --------
        $response = $this->json('POST', '/api/orders', [
            'customer_id' => $customer1['customer_id'],
            'creation_date' => date("Y-m-d"),
            'delivery_address' => null,
            'order_details' => [
                [
                    'quantity' => random_int(1, 100),
                    'product_id' => $product1['product_id']
                ]
            ],
        ]);

        
        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'delivery_address' => ['The delivery address field is required.']
                ]
            ]);
        // invalida address longer than 191
        $response = $this->json('POST', '/api/orders', [
            'customer_id' => $customer1['customer_id'],
            'creation_date' => date("Y-m-d"),
            'delivery_address' => str_random(192),
            'order_details' => [
                [
                    'quantity' => random_int(1, 100),
                    'product_id' => $product1['product_id']
                ]
            ],
        ]);

        
        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'delivery_address' => ['The delivery address may not be greater than 191 characters.']
                ]
            ]);
        
    // without orders details
    $response = $this->json('POST', '/api/orders', [
        'customer_id' => $customer1['customer_id'],
        'creation_date' => date("Y-m-d"),
        'delivery_address' => $faker->address
    ]);

    
    $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'order_details' => ['The order details field is required.']
            ]
        ]);

    // with orders details not array
    $response = $this->json('POST', '/api/orders', [
        'customer_id' => $customer1['customer_id'],
        'creation_date' => date("Y-m-d"),
        'delivery_address' => $faker->address,
        'order_details' => 3,
    ]);

    
    $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'order_details' => ['The order details must be an array.']
            ]
        ]);
    
    // orders details item has not quantity
    $response = $this->json('POST', '/api/orders', [
        'customer_id' => $customer1['customer_id'],
        'creation_date' => date("Y-m-d"),
        'delivery_address' => $faker->address,
        'order_details' => [
            [
                1,
                'product_id' => $product1['product_id'],
            ]
        ],
    ]);

    
    $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'order_details.0.quantity' => ['The order_details.0.quantity field is required.']
            ]
        ]);
    
    // orders details item has not product_id
    $response = $this->json('POST', '/api/orders', [
        'customer_id' => $customer1['customer_id'],
        'creation_date' => date("Y-m-d"),
        'delivery_address' => $faker->address,
        'order_details' => [
            [
                'quantity' => random_int(1, 100)
            ]
        ],
    ]);

    
    $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'order_details.0.product_id' => ['The order_details.0.product_id field is required.']
            ]
        ]);
    
    // order details has a product_id not valid for the customer
    $response = $this->json('POST', '/api/orders', [
        'customer_id' => $customer1['customer_id'],
        'creation_date' => date("Y-m-d"),
        'delivery_address' => $faker->address,
        'order_details' => [
            [
                'quantity' => random_int(1, 100),
                'product_id' => $product6['product_id'],
            ]
        ],
    ]);

    
    $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'order_details.0.product_id' => ['The selected order_details.0.product_id is invalid.']
            ]
        ]);
    
    // order details has more than 5 lines
    $response = $this->json('POST', '/api/orders', [
        'customer_id' => $customer1['customer_id'],
        'creation_date' => date("Y-m-d"),
        'delivery_address' => $faker->address,
        'order_details' => [
            [
                'quantity' => random_int(1, 100),
                'product_id' => $product1['product_id'],
            ],
            [
                'quantity' => random_int(1, 100),
                'product_id' => $product2['product_id'],
            ],
            [
                'quantity' => random_int(1, 100),
                'product_id' => $product3['product_id'],
            ],
            [
                'quantity' => random_int(1, 100),
                'product_id' => $product4['product_id'],
            ],
            [
                'quantity' => random_int(1, 100),
                'product_id' => $product5['product_id'],
            ],
            [
                'quantity' => random_int(1, 100),
                'product_id' => $product7['product_id'],
            ],
        ],
    ]);

    
    $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'order_details' => ['The order details may not have more than 5 items.']
            ]
        ]);
    }

    /**
     * @test
     */    
    public function can_create_an_order()
    {
        $faker = Factory::create();

        $customer = $this->create('Customer');        

        $product1 = $this->create('Product');
        $product2 = $this->create('Product');
        $product3 = $this->create('Product');
        $product4 = $this->create('Product');
        $product5 = $this->create('Product');

        $customer->products()->attach([
            $product1['product_id'],
            $product2['product_id'],
            $product3['product_id'],
            $product4['product_id'],
            $product5['product_id'],
        ]);

        $date = date("Y-m-d");
        $address = $faker->address;

        $quantity1 = random_int(1, 100);
        $quantity2 = random_int(1, 100);
        $quantity3 = random_int(1, 100);
        $quantity4 = random_int(1, 100);
        $quantity5 = random_int(1, 100);

        $total = (
            $product1['price'] * $quantity1 +
            $product2['price'] * $quantity2 +
            $product3['price'] * $quantity3 +
            $product4['price'] * $quantity4 +
            $product5['price'] * $quantity5
        );

        $order_details = [
            [
                'quantity' => $quantity1,
                'product_id' => $product1['product_id'],
            ],
            [
                'quantity' => $quantity2,
                'product_id' => $product2['product_id'],
            ],
            [
                'quantity' => $quantity3,
                'product_id' => $product3['product_id'],
            ],
            [
                'quantity' => $quantity4,
                'product_id' => $product4['product_id'],
            ],
            [
                'quantity' => $quantity5,
                'product_id' => $product5['product_id'],
            ],
        ];

        $response = $this->json('POST', '/api/orders', [
            'customer_id' => $customer['customer_id'],
            'creation_date' => $date,
            'delivery_address' => $address,
            'order_details' => $order_details,
        ]);
        
        $response->assertStatus(201);

        $this->assertDatabaseHas('order', [
            'customer_id' => $customer['customer_id'],
            'creation_date' => $date,
            'delivery_address' => $address,
            'total' => $total
        ]);

        $this->assertDatabasehas('order_detail', [
            'order_id' => 1,
            'order_detail_id' => 1,
            'product_id' => $product1['product_id'],
            'product_description' => $product1['product_description'],
            'price' => $product1['price'],
            'quantity' => $quantity1
        ]);
        $this->assertDatabasehas('order_detail', [
            'order_id' => 1,
            'order_detail_id' => 2,
            'product_id' => $product2['product_id'],
            'product_description' => $product2['product_description'],
            'price' => $product2['price'],
            'quantity' => $quantity2
        ]);
        $this->assertDatabasehas('order_detail', [
            'order_id' => 1,
            'order_detail_id' => 3,
            'product_id' => $product3['product_id'],
            'product_description' => $product3['product_description'],
            'price' => $product3['price'],
            'quantity' => $quantity3
        ]);
        $this->assertDatabasehas('order_detail', [
            'order_id' => 1,
            'order_detail_id' => 4,
            'product_id' => $product4['product_id'],
            'product_description' => $product4['product_description'],
            'price' => $product4['price'],
            'quantity' => $quantity4
        ]);
        $this->assertDatabasehas('order_detail', [
            'order_id' => 1,
            'order_detail_id' => 5,
            'product_id' => $product5['product_id'],
            'product_description' => $product5['product_description'],
            'price' => $product5['price'],
            'quantity' => $quantity5
        ]);


    }
}
