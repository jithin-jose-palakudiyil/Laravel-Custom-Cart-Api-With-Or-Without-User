<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'label' => fake()->name(),
            'type' => fake()->randomElement($array = array ('physical','digital',)),
            'DownloadURL' => fake()->url(),
            'Weight' =>  fake()->randomDigit(),
            'price'=>  fake()->numberBetween($min = 1000, $max = 9000),
        ];
    }
}
