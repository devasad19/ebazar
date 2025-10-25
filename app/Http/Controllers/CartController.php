<?php
// app/Http/Controllers/CartController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 🛒 Add to Cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $userId = Auth::id();

        if ($userId) {
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
    $user = Auth::user();
    $cartItems = CartItem::where('user_id', $user->id)->get();

    if ($cartItems->isEmpty()) {
        return back()->with('error', 'আপনার কার্ট খালি।');
    }

    $totalAmount = $cartItems->sum(function($item) {
        return $item->price * $item->quantity;
    });

    $order = Order::create([
        'user_id' => $user->id,
        'order_code' => 'ORD-' . strtoupper(Str::random(8)),
        'total_amount' => $totalAmount,
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

    // Clear cart
    CartItem::where('user_id', $user->id)->delete();

    // Redirect with order details
    return redirect()->route('home.order.done')->with([
        'success' => 'অর্ডার সফলভাবে সেভ হয়েছে!',
        'orderId' => $order->order_code,
        'total' => $totalAmount,
        'address' => $request->address,
        'phone' => $user->phone,
    ]);
}






    
}
