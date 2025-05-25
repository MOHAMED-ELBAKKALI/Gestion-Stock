<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function getCustomerOrders(Customer $customer): JsonResponse
    {
        $orders = $customer->orders()->get();
        return response()->json($orders);
    }

    public function ordersGreaterThanOrder60(): View
    {
        $order60Total = DB::table('product_orders')
            ->where('order_id', 60)
            ->value(DB::raw('SUM(price * quantity)'));

        $orders = Order::join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('product_orders', 'orders.id', '=', 'product_orders.order_id')
            ->select(
                'orders.id',
                DB::raw("CONCAT(customers.first_name, ' ', customers.last_name) as customer_name"),
                'orders.order_date',
                DB::raw('SUM(product_orders.price * product_orders.quantity) as total_amount')
            )
            ->groupBy('orders.id', 'customers.first_name', 'customers.last_name', 'orders.order_date')
            ->having('total_amount', '>', $order60Total)
            ->orderBy('orders.id')
            ->get();

        return view('orders.orders_greater_than_60', compact('orders', 'order60Total'));
    }

    public function getOrderDetails(Order $order): View
    {
        return view('orders._order_details', compact('order'));
    }

    public function orderTotals(): View
    {
        $orders = Order::join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('product_orders', 'orders.id', '=', 'product_orders.order_id')
            ->select(
                'orders.id',
                DB::raw("CONCAT(customers.first_name, ' ', customers.last_name) as customer_name"),
                'orders.order_date',
                DB::raw('SUM(product_orders.price * product_orders.quantity) as total_amount')
            )
            ->groupBy('orders.id', 'customers.first_name', 'customers.last_name', 'orders.order_date')
            ->orderBy('orders.id')
            ->get();

        return view('orders.order_totals', compact('orders'));
    }
}
