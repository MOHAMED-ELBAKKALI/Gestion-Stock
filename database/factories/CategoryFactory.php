<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */class CategoryFactory extends Factory
{
    protected $categories = [
        'Electronics',
        'Clothing',
        'Home Appliances',
        'Furniture',
        'Books',
        'Sports Equipment',
        'Toys',
        'Beauty & Personal Care',
        'Office Supplies',
        'Kitchen & Dining',
        'Automotive',
        'Garden & Outdoor',
        'Health & Wellness',
        'Pet Supplies',
        'Tools & Hardware'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement($this->categories)
        ];
    }
}
