<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
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

    protected $numProductsPerOrder = [1, 2, 3, 4, 5];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create categories from predefined list
        $categories = collect($this->categories)->map(function ($name) {
            return Category::create(['name' => $name]);
        });

        // Create base data
        $suppliers = Supplier::factory(10)->create();
        $stores = Store::factory(5)->create();

        // Create products with existing suppliers and categories
        $products = Product::factory(50)
            ->recycle($suppliers)
            ->recycle($categories)
            ->create();

        // Create stocks for products in stores
        Stock::factory(100)
            ->recycle($products)
            ->recycle($stores)
            ->create();

        // Create customers and their orders
        $customers = Customer::factory(50)->create();
        $products = Product::all();
        
        // Create orders and attach random products to each order
        Order::factory(100)->create()->each(function ($order) use ($products) {
            // Get a random number of products for this order
            $numProducts = fake()->randomElement($this->numProductsPerOrder);
            
            // Get random products
            $orderProducts = $products->random($numProducts);
            
            // Attach products to order with quantity and price
            foreach ($orderProducts as $product) {
                $quantity = fake()->numberBetween(1, 5);
                $price = $product->price * $quantity;
                
                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $price,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });

        // Create transactions for orders and stocks
        Transaction::factory(50)->create();
    }
}
