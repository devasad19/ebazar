<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\BazarController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Dashboard\UserDashboardController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [HomeController::class, 'frontdHome'])->name('front_home');
Route::get('/products/filter', [HomeController::class, 'filterProducts'])->name('products.filter');
Route::get('product/{id}', [HomeController::class, 'frontdProductDetails'])->name('home.product.details');
 

 

// routes/web.php
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/fetch', [CartController::class, 'index'])->name('cart.fetch');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
 
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/', [CartController::class, 'destroy'])->name('cart.remove');
Route::post('/save-order', [CartController::class, 'placeOrder'])->name('place.order');

 


// ========================= rider routes =================
Route::get('rider/dashboard', [RiderController::class, 'riderDashboard'])->name('rider.dashboard');
Route::get('rider/orders', [RiderController::class, 'riderOrders'])->name('rider.orders');
Route::get('rider/products', [RiderController::class, 'riderProducts'])->name('rider.products');
Route::post('rider/products/store', [RiderController::class, 'riderProductStore'])->name('rider.products.store');
Route::get('rider/earnings', [RiderController::class, 'riderEarnings'])->name('rider.earnings');
Route::get('rider/support', [RiderController::class, 'riderSupport'])->name('rider.support');
Route::get('rider/settings', [RiderController::class, 'riderSettings'])->name('rider.settings');

// Rider pending order list (for auto-load)
Route::get('/rider/orders/pending', [RiderController::class, 'pendingOrders'])->name('rider.orders.pending');

// Rider accept order
Route::post('/rider/orders/accept', [RiderController::class, 'acceptOrder'])->name('rider.orders.accept');
// routes/web.php
Route::post('/rider/orders/deliver/{id}', [RiderController::class, 'markAsDelivered'])
    ->name('rider.orders.deliver');





Route::get('/register-user', [RegisterController::class, 'create'])->name('user.register');
Route::post('/register-user', [RegisterController::class, 'store'])->name('user.register.store');


Route::get('rider/{id}', [HomeController::class, 'frontdRiderDetails'])->name('riders.show');


Route::get('place-order', [HomeController::class, 'homePlaceOrder'])->name('home.place.order');
Route::get('order-success', [HomeController::class, 'homeOrderDone'])->name('home.order.done');


Route::get('/become-rider', [RiderController::class, 'riderRegForm'])->name('rider.register');
Route::post('/become-rider', [RiderController::class, 'reiderStore'])->name('rider.register.store');

// admin dashboard routes
Route::get('admin/dashboard', [AdminDashboardController::class, 'adminDashboard'])->name('admin.dashboard');

Route::get('admin/dashboard/manage_products', [AdminDashboardController::class, 'adminManageProducts'])->name('admin.manage_products');
Route::get('admin/dashboard/create-product', [AdminDashboardController::class, 'adminManageCreateProducts'])->name('admin.product.create');
Route::post('admin/dashboard/store-product', [AdminDashboardController::class, 'adminStoreProducts'])->name('admin.products.store');
Route::get('admin/dashboard/all_orders', [AdminDashboardController::class, 'adminAllOrders'])->name('admin.all_orders');
Route::get('admin/dashboard/rider_list', [AdminDashboardController::class, 'adminRiderList'])->name('admin.rider_list');
Route::get('admin/dashboard/customer_list', [AdminDashboardController::class, 'adminCustomerList'])->name('admin.customer_list');
Route::get('admin/dashboard/staff_list', [AdminDashboardController::class, 'adminStaffList'])->name('admin.staff_list');
Route::get('admin/dashboard/settings', [AdminDashboardController::class, 'adminSettings'])->name('admin.settings');


Route::get('admin/dashboard/rider/slug', [AdminDashboardController::class, 'riderProfile'])->name('admin.rider.profile');
 
Route::get('/admin/live/orders', [AdminDashboardController::class, 'adminLiveOrders'])->name('admin.orders.live');

// ========================= backend =================
Route::get('admin/dashboard/manage-bazar', [BazarController::class, 'adminManageBazar'])->name('admin.manage_bazar');
   
Route::post('/bazars/store', [BazarController::class, 'store'])->name('admin.bazars.store');
Route::get('/bazars/{id}/edit', [BazarController::class, 'edit'])->name('admin.bazars.edit');
Route::get('/bazars/{id}', [BazarController::class, 'destroy'])->name('bazar.delete');


Route::get('/products', [ProductController::class, 'index'])->name('manage_products');
Route::post('/products/store', [ProductController::class, 'store'])->name('admin.products.store');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::post('/products/{id}/update', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('/riders', [RiderController::class, 'index'])->name('admin.riders.index');
Route::post('/riders', [RiderController::class, 'riderStore'])->name('admin.riders.store');
Route::delete('/riders/{id}', [RiderController::class, 'destroy'])->name('admin.riders.destroy');




// user dashboard routes
Route::get('user/dashboard', [UserDashboardController::class, 'userDashboard'])->name('user.dashboard');
Route::get('user/dashboard/my-orders', [UserDashboardController::class, 'myOrders'])->name('user.my_orders');
Route::get('user/dashboard/my-cart', [UserDashboardController::class, 'myCart'])->name('user.my_cart');
Route::get('user/dashboard/settings', [UserDashboardController::class, 'mySettings'])->name('user.settings');


    Route::get('order/ajax/details', [UserDashboardController::class, 'details'])->name('rider.order.details');
    Route::post('order/user/accept', [UserDashboardController::class, 'accept'])->name('rider.order.accept');
    Route::post('user/order/cancell', [UserDashboardController::class, 'orderCancell'])->name('rider.order.cancell');


 

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
