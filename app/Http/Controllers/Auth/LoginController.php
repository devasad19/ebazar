<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Role;

class LoginController extends Controller
{
    use \Illuminate\Foundation\Auth\AuthenticatesUsers;

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

            // যদি user authenticated method থাকে, call করুন
 
                switch (Auth::user()->role->name ?? '') {
                    case 'rider':
                        return redirect('rider/dashboard');
                    case 'user':
                        return redirect('user/dashboard');
                    case 'admin':
                        return redirect('admin/dashboard');
                    default:
                        return redirect()->intended($this->redirectTo);
                }


        }

        throw ValidationException::withMessages([
            'phone' => 'ফোন নম্বর বা পাসওয়ার্ড সঠিক নয়।',
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        // রোল অনুযায়ী redirect
        switch ($user->role->name ?? '') {
            case 'rider':
                return redirect('/dashboard/rider');
            case 'user':
                return redirect('/dashboard/user');
            case 'admin':
                return redirect('/dashboard/admin');
            default:
                return redirect('/');
        }
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
