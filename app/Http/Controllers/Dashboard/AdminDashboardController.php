<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Bazar;
use App\Models\Rider;
use App\Models\Order;

class AdminDashboardController extends Controller
{

    public function adminDashboard(){
        
        return view('backend.admin-dashboard.index');
    }



    public function adminManageProducts(){
        
   $data['products'] = Product::where('status', 'active')->get();



        return view('backend.admin-dashboard.products.manage_products', $data);
    }

    public function adminManageCreateProducts(){
        $data['categories'] = Category::orderBy('id', 'desc')->get();
        $data['riders'] = Rider::orderBy('id', 'desc')->get();
        $data['bazars'] = Bazar::where('status', 'Active')->get();

        return view('backend.admin-dashboard.products.add_products', $data);
    }

    public function adminAllOrders(){

        $data['orders'] = [];
 
        return view('backend.admin-dashboard.all_orders', $data);
    }


    public function adminLiveOrders()
    {
        $orders = Order::with(['user', 'rider', 'items.product'])->latest()->get();

        return response()->json(['orders' => $orders]);
    }





    public function adminRiderList(){

        $data['riders'] = collect([
            (object)[
                'id' => 1,
                'name' => 'মোঃ রহমান',
                'total_delivered' => 120,
                'on_time_delivery' => 110,
                'pending_orders' => 5,
                'cancel_delivery' => 5
            ],
            (object)[
                'id' => 2,
                'name' => 'সেলিম আহমেদ',
                'total_delivered' => 85,
                'on_time_delivery' => 80,
                'pending_orders' => 3,
                'cancel_delivery' => 2
            ],
            (object)[
                'id' => 3,
                'name' => 'জামিল হোসেন',
                'total_delivered' => 150,
                'on_time_delivery' => 140,
                'pending_orders' => 7,
                'cancel_delivery' => 3
            ]
        ]);


 
        return view('backend.admin-dashboard.rider_list', $data);
    }


    public function adminCustomerList(){

        $data['customers'] = [
            (object)[
                'id' => 1,
                'name' => 'Md. Asad',
                'email' => 'asad@mail.com',
                'phone' => '01710000001',
                'total_orders' => 12,
                'pending_orders' => 2,
                'status' => 'active'
            ],
            (object)[
                'id' => 2,
                'name' => 'Rina Akter',
                'email' => 'rina@mail.com',
                'phone' => '01710000002',
                'total_orders' => 8,
                'pending_orders' => 1,
                'status' => 'active'
            ],
            (object)[
                'id' => 3,
                'name' => 'Jamal Hossain',
                'email' => 'jamal@mail.com',
                'phone' => '01710000003',
                'total_orders' => 5,
                'pending_orders' => 0,
                'status' => 'inactive'
            ]
        ];
 
        return view('backend.admin-dashboard.customer_list', $data);
    }


    public function riderProfile(){
        $data['rider'] = (object)[
            'id' => 1,
            'name' => 'Rider Mohsin',
            'email' => 'mohsin@example.com',
            'phone' => '01712345678',
            'avatar' => 'https://via.placeholder.com/100',
            'total_delivered' => 120,
            'on_time_delivery' => 110,
            'on_time_percentage' => 92,
            'pending_orders' => 8,
            'cancel_orders' => 2,
            'total_earnings' => 15000,
            'recent_orders' => [
                (object)[
                    'id' => 12345,
                    'customer_name' => 'Md. Asad',
                    'product_name' => 'Fresh Tomato',
                    'quantity' => 3,
                    'price' => 150,
                    'status' => 'Delivered'
                ],
                (object)[
                    'id' => 12346,
                    'customer_name' => 'Rina Akter',
                    'product_name' => 'Brinjal',
                    'quantity' => 2,
                    'price' => 80,
                    'status' => 'Pending'
                ]
                ],
                
            'created_at' => '2025-10-14 02:41:05',
        ];

 
        return view('backend.rider_profile', $data);
    }


    public function adminStaffList(){
        $data['staffs'] = (object)[
            (object)[
                'id' => 1,
                'name' => 'Md. Asad',
                'email' => 'asad@mail.com',
                'phone' => '01710000001',
                'role' => 'manager',
                'status' => 'active'
            ],
            (object)[
                'id' => 2,
                'name' => 'Rina Akter',
                'email' => 'rina@mail.com',
                'phone' => '01710000002',
                'role' => 'accountant',
                'status' => 'active'
            ],
            (object)[
                'id' => 3,
                'name' => 'Jamal Hossain',
                'email' => 'jamal@mail.com',
                'phone' => '01710000003',
                'role' => 'assistant',
                'status' => 'inactive'
            ]
        ];


 
        return view('backend.admin-dashboard.staff_list', $data);
    }

    public function adminSettings(){
 


 
        return view('backend.admin-dashboard.admin_settings');
    }









}
