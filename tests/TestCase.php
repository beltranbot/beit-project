<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function create(string $model, array $attributes = [], $resource = true)
    {
        if ($model === 'CustomerProduct') {
            $customer_products = [];
            foreach ($attributes['products'] as $product) {
                $resourceModel = factory("App\\Models\\$model")->create([
                    'product_id' => $product
                ]);
                $customer_products[] = $resourceModel;
            }

            return $customer_products;
        }
        $resourceModel = factory("App\\Models\\$model")->create($attributes);
        $resourceClass = "App\\Http\\Resources\\$model";

        if (!$resource) {
            return $resourceModel;
        }

        return new $resourceClass($resourceModel);
    }
}
