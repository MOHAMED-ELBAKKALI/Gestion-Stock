<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a paginated list of customers.
     */
    public function index(): View
    {
        $customers = Customer::paginate(20);
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): View
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer.
     */
    public function store(CustomerRequest $request): RedirectResponse
    {
        Customer::create($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Show the form for editing a customer.
     */
    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update a customer's data.
     */
    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Show confirmation form for deleting a customer.
     */
    public function delete(Customer $customer): View
    {
        return view('customers.delete', compact('customer'));
    }

    /**
     * Delete a customer.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Find customers who ordered the same products as Annabel Stehr.
     */
    public function sameProductsCustomers(): View
    {
        $customer = Customer::whereRaw("CONCAT(first_name, ' ', last_name) = ?", ['Annabel Stehr'])->first();

        if (!$customer) {
            return view('customers.same_products_customers', ['customers' => collect()]);
        }

        $productIds = Order::join('product_orders', 'orders.id', '=', 'product_orders.order_id')
            ->where('orders.customer_id', $customer->id)
            ->pluck('product_orders.product_id');

        $customers = DB::table('orders')
            ->join('product_orders', 'orders.id', '=', 'product_orders.order_id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('products', 'product_orders.product_id', '=', 'products.id')
            ->whereIn('product_orders.product_id', $productIds)
            ->where('orders.customer_id', '!=', $customer->id)
            ->select([
                DB::raw("CONCAT(customers.first_name, ' ', customers.last_name) as customer_name"),
                'customers.email',
                'products.name as product_name',
                'orders.order_date'
            ])
            ->orderBy('customer_name')
            ->get();

        return view('customers.same_products_customers', compact('customers'));
    }

    /**
     * Search customers by term (name, email, phone, address) and return JSON.
     */
    public function searchTerm(Request $request, $term)
    {
        $customers = Customer::where('first_name', 'like', "%{$term}%")
            ->orWhere('last_name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('phone', 'like', "%{$term}%")
            ->orWhere('address', 'like', "%{$term}%")
            ->get();

        return response()->json($customers);
    }

    /**
     * Search customers with pagination and return JSON.
     */
    public function search(Request $request)
    {
        $term = $request->input('term');

        $customers = Customer::where('first_name', 'like', "%{$term}%")
            ->orWhere('last_name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('phone', 'like', "%{$term}%")
            ->orWhere('address', 'like', "%{$term}%")
            ->paginate(10);

        return response()->json([
            'customers' => $customers->items(),
            'pagination' => [
                'total' => $customers->total(),
                'per_page' => $customers->perPage(),
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'from' => $customers->firstItem(),
                'to' => $customers->lastItem(),
                'links' => $customers->linkCollection()->toArray()
            ]
        ]);
    }
}
