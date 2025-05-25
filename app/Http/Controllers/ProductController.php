<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        $products = Product::with(['category', 'supplier', 'stock'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate(10);

        $categories = Category::all();
        $suppliers = Supplier::all();

        if ($request->ajax()) {
            return response()->json([
                'products' => $products->items(),
                'pagination' => [
                    'total' => $products->total(),
                    'per_page' => $products->perPage(),
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                ]
            ]);
        }

        return view('products.index', compact('products', 'categories', 'suppliers'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(ProductRequest $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('picture')) {
            $validated['picture'] = $request->file('picture')->store('products', 'public');
        }

        $product = Product::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'product' => $product]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): \Illuminate\Http\JsonResponse
    {
        return response()->json($product);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('picture')) {
            if ($product->picture) {
                Storage::disk('public')->delete($product->picture);
            }

            $validated['picture'] = $request->file('picture')->store('products', 'public');
        }

        $product->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'product' => $product]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): \Illuminate\Http\JsonResponse
    {
        if ($product->picture) {
            Storage::disk('public')->delete($product->picture);
        }

        $product->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Display the number of orders per product.
     */
    public function ordersCount(): View
    {
        $products = Product::leftJoin('product_orders', 'products.id', '=', 'product_orders.product_id')
            ->groupBy('products.id', 'products.name')
            ->selectRaw('products.name, COUNT(product_orders.order_id) as orders_count')
            ->get();

        return view('products.orders_count', compact('products'));
    }

    /**
     * Display products with more than 6 orders.
     */
    public function productsMoreThan6Orders(): View
    {
        $products = Product::leftJoin('product_orders', 'products.id', '=', 'product_orders.product_id')
            ->groupBy('products.id', 'products.name')
            ->selectRaw('products.name, COUNT(product_orders.order_id) as orders_count')
            ->havingRaw('COUNT(product_orders.order_id) > 6')
            ->get();

        return view('products.products_more_than_6_orders', compact('products'));
    }
}
