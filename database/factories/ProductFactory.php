<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->name();
        return [
            'product_id' => self::getProductId($name),
            'product_name' => $name,
            'product_price' => $this->faker->randomDigit,
            'description' => $this->faker->text(),
        ];
    }

    private static function getProductId($name)
    {
        return  Product::convertIdByProductName($name);
    }
}
