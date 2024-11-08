<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart;
        // return $cart;
        // return $cart->products; // ? Testing

        $data = [
            'cart' => $cart,
            'user' => $user,
        ];

        return view('carts.index')->with($data);
    }

    public static function middleware()
    {
        return [
            new Middleware('auth', except: ['store', 'remove', 'clear']),
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        // return request();
        $values = request()->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $productId = $values['product_id'];
        $product = Product::find($productId);

        if ($product->stock < $values['quantity']) {
            return back()->with('error', 'Out of stock');
        }

        $user = Auth::user();
        $cart = $user->cart;
        if ($cart == null) {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->save();
        }

        // check if this product has already been added to this cart
        $isInCartAlready = $cart->products()->get()->pluck('id')->contains($productId);
        if ($isInCartAlready) {
            return back()->with('error', 'Product already in cart');
        }

        $cart->products()->attach(
            $productId,
            ['quantity' => $values['quantity']]
        );
        $cart->update();

        $product->stock -= $values['quantity'];
        $product->update();

        return back()->with('success', 'Product added to cart');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }

    /**
     * Remove a product from the cart
     */
    public function remove(Cart $cart, Product $product)
    {
        $product->stock += $cart->products()->find($product->id)->pivot->quantity;
        $product->update();
        $cart->products()->detach($product->id);

        return redirect()->route('cart.index')->with('success', 'Product removed from cart');
    }

    /**
     * Clear the cart
     */
    public function clear(?Cart $cart)
    {
        if ($cart == null) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        foreach ($cart->products as $product) {
            $product->stock += $product->pivot->quantity;
            $product->update();
        }
        $cart->products()->detach();

        return redirect()->route('cart.index')->with('success', 'Cart cleared');
    }

    public function addQuantity($id)
    {
        $user = Auth::user();
        $cart = $user->cart;
        $product = Product::find($id);

        $addproduct = $cart->products()->where('product_id', $product->id)->first();
        if ($addproduct && $product->stock > 0) {
            $quantity = $addproduct->pivot->quantity;
            $cart->products()->updateExistingPivot($product->id, ['quantity' => $quantity + 1]);
            $product->stock -= 1;
            $product->save();
        } else if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Product is out of stock');
        }

        $cart->save();
        return redirect()->back()->with('success', 'You added one quantity to your cart');
    }
    public function removeQuantity($id)
    {
        $user = Auth::user();
        $cart = $user->cart;
        $product = Product::find($id);

        $addproduct = $cart->products()->where('product_id', $product->id)->first();
        if ($addproduct && $product->stock > 0) {
            $quantity = $addproduct->pivot->quantity;
            $cart->products()->updateExistingPivot($product->id, ['quantity' => $quantity - 1]);
            $product->stock += 1;
            $product->save();
        } else if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Product is out of stock');
        }

        $cart->save();
        return redirect()->back()->with('success', 'You removed one quantity to your cart');
    }

    public function verify_payment()
    {
        // Validate for reference and cart_id
        request()->validate([
            'reference' => 'required',
            'cart_id' => 'required|exists:carts,id',
        ]);

        // Call paystack api
        $reference = request()->reference;
        $url = "https://api.paystack.co/transaction/verify/$reference";
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('app.paystack.secret_key')
        ])->get($url);
        $result = $response->json();

        // return $result; // ? Debug

        // Verify transaction with result
        if ($result['data']['status'] == "success") {
            /* Divide the amount by hundred to get the actual amount
            because paystack needs you to multiply by 100 when
            making the payment to get nearest currency
            */
            $amount = $result['data']['amount'] / 100;
            $ref = $result['data']['reference'];
            $currency = $result['data']['currency'];
            $email = $result['data']['customer']['email'];
            $cart = Cart::find(request()->cart_id);

            // Check if details match
            $referenceIsValid = $ref == $reference;
            // Round off both amount to nearest 2 dp before checking
            $amountIsValid = round($amount, 2) == round($cart->total_price, 2);
            $currencyIsValid = $currency == "NGN";
            $emailIsValid = $email == $cart->user->email;

            if ($referenceIsValid && $amountIsValid && $currencyIsValid && $emailIsValid) {
                // Wow, Valid!
                // Update checked_out for this cart
                $cart->checked_out = true;
                $cart->checked_out_at = now();
                $cart->reference = $reference;
                $cart->update();

                // Redirect to cart with success
                return redirect()->route('cart.index')->with('success', 'Payment successful, your order is being processed.');
            }

            // Redirect to cart with error
            return redirect()->route('cart.index')->with('error', 'Invalid payment, please contact our support.');
        }

        // Redirect to cart with error
        return redirect()->route('cart.index')->with('error', 'Payment failed, please try again.');
    }

    public function addQuantity($id)
    {
        $user = Auth::user();
        $cart = $user->cart;
        $product = Product::find($id);

        $addproduct = $cart->products()->where('product_id', $product->id)->first();
        if ($addproduct && $product->stock > 0) {
            $quantity = $addproduct->pivot->quantity;
            $cart->products()->updateExistingPivot($product->id, ['quantity' => $quantity + 1]);
            $product->stock -= 1;
            $product->save();
        } else if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Product is out of stock');
        }

        $cart->save();
        return redirect()->back()->with('success', 'You added one quantity to your cart');
    }

    public function removeQuantity($id)
    {
        $user = Auth::user();
        $cart = $user->cart;
        $product = Product::find($id);

        $addproduct = $cart->products()->where('product_id', $product->id)->first();
        if ($addproduct && $product->stock > 0) {
            $quantity = $addproduct->pivot->quantity;
            $cart->products()->updateExistingPivot($product->id, ['quantity' => $quantity - 1]);
            $product->stock += 1;
            $product->save();
        } else if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Product is out of stock');
        }

        $cart->save();
        return redirect()->back()->with('success', 'You removed one quantity to your cart');
    }
}
