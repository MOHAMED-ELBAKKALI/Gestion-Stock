<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function productsByCategory()
    {
        return view('products.by-category', [
            'categories' => Category::all(),
            'products' => collect()
        ]);
    }

    public function getProductsByCategory(Category $category)
    {
        return view('products.by-category', [
            'categories' => Category::all(),
            'products' => $category->products
        ]);
    }
}
