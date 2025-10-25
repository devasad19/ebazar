<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{

    public function userDashboard(){
        
        return view('backend.user-dashboard.index');
    }

    public function myOrders(){
        
        return view('backend.user-dashboard.my_orders');
    }

    public function mySettings(){
        
        return view('backend.user-dashboard.my_settings');
    }

    public function myCart(){
        


        $cartItems = [
            [
                'id' => 1,
                'name' => 'তাজা বেগুন',
                'quantity' => 2,
                'price' => 60,
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSK5CGnmWFlnmyNeLRUot2wiAvakCoICLmKEA&s'
            ],
            [
                'id' => 2,
                'name' => 'টমেটো',
                'quantity' => 3,
                'price' => 80,
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQbC47y2YlKDgBzBmLitYb75dDU6F028k7oGQ&s'
            ],
            [
                'id' => 3,
                'name' => 'রুই মাছ',
                'quantity' => 1,
                'price' => 380,
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT9Rg2Vv4nM80zyIgVtw_4vNrAzSXBRWIYClg&s'
            ]
        ];

        $total = collect($cartItems)->sum(function($item){
            return $item['price'] * $item['quantity'];
        });

        return view('backend.user-dashboard.my_cart', compact('cartItems', 'total'));
    }









}
