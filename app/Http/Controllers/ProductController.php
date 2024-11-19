<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Notifications\NewProductNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Notification;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('role.is_admin', ['create', 'edit', 'store', 'update', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('stock', '>', 0)->latest()->get();
        // $products = Product::where('stock', '>', 0)->inCart()->latest()->get();

        // return $products; // ? Testing

        $data = [
            'products' => $products,
            'currentUserName' => auth()->user()->name ?? 'Guest',
            'currentDateTime' => Carbon::now()->translatedFormat('l, F, Y'),
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

        // Fire notification
        $users = User::all();
        Notification::send($users, new NewProductNotification($product));

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
