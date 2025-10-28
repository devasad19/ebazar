<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Role;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

 

    /**
     * Override the username field to use phone.
     */
    public function username()
    {
        return 'phone';
    }


public function login(Request $request)
{
    $credentials = $request->validate([
        'phone' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        $user = Auth::user();

        // ✅ Guest session cart merge into database cart
        $sessionCart = session()->get('cart', []);
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $item) {
                \App\Models\CartItem::updateOrCreate(
                    ['user_id' => $user->id, 'product_id' => $productId],
                    [
                        'quantity' => \DB::raw('quantity + ' . $item['quantity']),
                        'price' => $item['price'],
                    ]
                );
            }
            session()->forget('cart'); // ✅ Clear guest cart after merge
        }


 
        // ✅ Redirect logic
        if (session()->has('url.intended')) {
            return redirect()->intended();
        }

        switch ($user->role->name ?? '') {
            case 'rider':
                return redirect('rider/dashboard');
            case 'user':
                return redirect('user/dashboard');
            case 'admin':
                return redirect('admin/dashboard');
            default:
                return redirect('/');
        }
    }

    throw ValidationException::withMessages([
        'phone' => 'ফোন নম্বর বা পাসওয়ার্ড সঠিক নয়।',
    ]);
}

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
