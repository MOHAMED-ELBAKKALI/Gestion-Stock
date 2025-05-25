<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard');
    }

    public function customers(): View
    {
        $customers = Customer::paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function suppliers(): View
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function products(): View
    {
        $products = Product::with(['category', 'supplier', 'stock'])->get();
        return view('products.index', compact('products'));
    }

    public function productsBySupplier(): View
    {
        $suppliers = Supplier::all();
        return view('products.by-supplier', compact('suppliers'));
    }

    public function getProductsBySupplier(Supplier $supplier): View
    {
        $products = Product::with(['stock', 'category'])
            ->where('supplier_id', $supplier->id)
            ->get();

        return view('products._products_by_supplier', compact('products'));
    }

    public function productsByStore(): View
    {
        $stores = Store::all();
        return view('products.by-store', compact('stores'));
    }

    public function getProductsByStore(Store $store): JsonResponse
    {
        $products = Product::with(['category', 'stock'])
            ->whereHas('stock', fn($query) => $query->where('store_id', $store->id))
            ->get();

        return response()->json($products);
    }

    public function orders(): View
    {
        return view('orders.index');
    }
}
