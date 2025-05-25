<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductOrderController extends Controller
{
    public function index()
    {
        $orders = Order::join('product_orders', 'product_orders.order_id', '=', 'orders.id')
            ->join('products', 'product_orders.product_id', '=', 'products.id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->whereMonth('orders.order_date', 7)
            ->whereYear('orders.order_date', 2024)
            ->orderByRaw("CONCAT(customers.first_name, ' ', customers.last_name) DESC")
            ->select(
                'products.name as product_name',
                DB::raw("CONCAT(customers.first_name, ' ', customers.last_name) as customer_name"),
                'categories.name as category_name',
                DB::raw("CONCAT(suppliers.first_name, ' ', suppliers.last_name) as supplier_name"),
                'orders.order_date'
            )
            ->get();

        return view('products.ordered_products', compact('orders'));
    }
}
