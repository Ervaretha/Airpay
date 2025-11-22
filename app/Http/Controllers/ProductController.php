<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Validation\Rule;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:products,name',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
    ]);

    $generatedCode = 'PRD-' . strtoupper(uniqid());

    Product::create([
        'name'        => $request->name,
        'code'        => $generatedCode,
        'category_id' => $request->category_id,
        'price'       => $request->price,
        'stock'       => $request->stock,
    ]);

    return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
}

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('products', 'name')->ignore($product->id),
        ],
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
    ]);

    $product->update([
        'name'        => $request->name,
        'category_id' => $request->category_id,
        'price'       => $request->price,
        'stock'       => $request->stock,
    ]);

    return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate.');
}


    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}