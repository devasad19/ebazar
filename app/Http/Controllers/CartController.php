<?php
// app/Http/Controllers/CartController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\CustomProduct;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    // 🛒 Add to Cart
    public function addToSession22(Request $request)
    {
        
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'image' => $product->image
            ];
        }

        session()->put('cart', $cart);

        return response()->json(['success' => true, 'message' => 'পণ্যটি কার্টে যোগ হয়েছে!']);
    }

    // 🛒 Add to Cart
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $userId = Auth::id();

        if (auth()->check()) {
            $cartItem = CartItem::updateOrCreate(
                ['user_id' => $userId, 'product_id' => $product->id],
                [
                    'quantity' => \DB::raw('quantity + '.$request->quantity),
                    'price' => $product->price
                ]
            );
        } else {
            // 🧠 Guest user — store in session
            $cart = session()->get('cart', []);
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] += $request->quantity;
            } else {
                $cart[$product->id] = [
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $request->quantity,
                    'image' => $product->image
                ];
            }
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true, 'message' => 'পণ্যটি কার্টে যোগ হয়েছে!']);
    }


// public function sessionViewCart()
// {
//     $userId = Auth::id();
//     if ($userId) {
//         $cartItems = CartItem::where('user_id', $userId)->get();
//     } else {
//         $cartItems = session('cart', []);
//     }

//     return view('cart.index', compact('cartItems'));
// }


    // 🔢 Count total items
    public function count()
    {
        if (Auth::check()) {
            $count = CartItem::where('user_id', Auth::id())->sum('quantity');
        } else {
            $count = collect(session('cart', []))->sum('quantity');
        }

        return response()->json(['count' => $count]);
    }

    // 📦 Show cart items (optional)
public function index()
{
    if (auth()->check()) {
        $items = \App\Models\CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();
    } else {
        $items = collect();
    }

    return response()->json([
        'items' => $items,
        'count' => $items->sum('quantity'),
        'total' => $items->sum(fn($item) => $item->price * $item->quantity),
    ]);
}

    // 📦 Show cart items (optional)
public function viewCardItems()
{
    if (auth()->check()) {
        $userId = auth()->id();
        $items = \App\Models\CartItem::with('product')->where('user_id', $userId)->get();
        return response()->json([
            'source' => 'database',
            'items' => $items,
            'count' => $items->sum('quantity')
        ]);
    } else {
        $cart = session()->get('cart', []);
        return response()->json([
            'source' => 'session',
            'items' => $cart,
            'count' => collect($cart)->sum('quantity')
        ]);
    }
}

// 🗑️ Remove item from session cart
public function removeSessionItem($productId)
{

    if (auth()->check()) {
        $userId = auth()->id();
        CartItem::where('user_id', $userId)
        ->where('product_id', $productId)
        ->delete();
         
        $cart = CartItem::where('user_id', $userId)
            ->get();

    } else {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]); // পণ্যটা কার্ট থেকে মুছে ফেলো
            session()->put('cart', $cart);
        }
    }

    return response()->json([
        'success' => true,
        'message' => '🗑️ পণ্যটি কার্ট থেকে মুছে ফেলা হয়েছে!',
        'cart' => $cart
    ]);
}


public function update(Request $request)
{

    $cart = CartItem::findOrFail($request->id);
    $cart->quantity = $request->quantity;
    $cart->save();

    $total = CartItem::where('user_id', auth()->id())
                ->get()
                ->sum(fn($c) => $c->price * $c->quantity);

    return response()->json([
        'success' => true,
        'itemTotal' => $cart->price * $cart->quantity,
        'cartTotal' => $total
    ]);
}

public function destroy(Request $request)
{
    $cart = CartItem::findOrFail($request->id);
    $cart->delete();

    $total = CartItem::where('user_id', auth()->id())
                ->get()
                ->sum(fn($c) => $c->price * $c->quantity);

    return response()->json([
        'success' => true,
        'cartTotal' => $total
    ]);
}


public function placeOrder(Request $request)
{

    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'অর্ডার করতে, আগে সিস্টেমে লগইন করুন!');
    }
 

    $user = Auth::user();
    $cartItems = CartItem::where('user_id', $user->id)->get();

    if ($cartItems->isEmpty()) {
        return back()->with('error', 'আপনার কার্ট খালি।');
    }

    $totalAmount = $cartItems->sum(function($item) {
        return $item->price * $item->quantity;
    });
 
    $total = $totalAmount + $request->customTotal;

    $order = Order::create([
        'user_id' => $user->id,
        'order_code' => 'ORD-' . strtoupper(Str::random(8)),
        'total_amount' => $total,
        'payment_method' => 'Cash On Delivery',
        'delivery_address' => $request->address,
    ]);

    // Save order items
    foreach($cartItems as $cart) {
        $order->items()->create([
            'product_id' => $cart->product_id,
            'quantity' => $cart->quantity,
            'price' => $cart->price,
        ]);
    }
 
        // ✅ Save custom products
    if ($request->filled('custom_products')) {
        $customProducts = $request->input('custom_products', []);

        foreach ($customProducts as $product) {
            // প্রত্যেকটি পণ্যের ডাটা ধরুন
            $name = $product['name'] ?? null;
            $qty = $product['qty'] ?? null;
            $unit = $product['unit'] ?? null;
            $price = $product['price'] ?? null;

            if ($name && $unit) {
                CustomProduct::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'name' => $name,
                    'quantity' => $qty,
                    'unit' => $unit,
                    'price' => $price,
                ]);
            }
        }
    }





    // Clear cart
    CartItem::where('user_id', $user->id)->delete();

    // Redirect with order details
    return redirect()->route('home.order.done')->with([
        'success' => 'অর্ডার সফলভাবে সেভ হয়েছে!',
        'orderId' => $order->order_code,
        'total' => $total,
        'address' => $request->address,
        'phone' => $user->phone,
    ]);
}






    
}
