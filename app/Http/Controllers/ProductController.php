<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('stock', '>', 0)->latest()->get();

        $data = [
            'products' => $products,
        ];

        return view('products.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request; // ? Testing

        // Validate the form
        $values = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'required|image',
            'stock' => 'required|integer',
        ]);

        // return $values; // ? Testing

        // Save the image
        $imagePath = $request->image->store('products', 'public');

        // Save the product
        $product = new Product();
        $product->name = $values['name'];
        $product->description = $values['description'];
        $product->price = $values['price'];
        $product->image = $imagePath;
        $product->stock = $values['stock'];
        $product->save();

        // Redirect to the products.index route with a success message
        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // return $request;

        // Validate the form
        $values = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'nullable|integer',
        ]);

        // Update the product
        $product->name = $values['name'];
        $product->description = $values['description'];
        $product->price = $values['price'];

        // Update the image
        if ($request->hasFile('image')) {
            $imagePath = $request->image->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->stock = $values['stock'];

        $product->update();

        return back()->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect(route('products.index'))->with('success', 'Product deleted successfully!');
    }
}
