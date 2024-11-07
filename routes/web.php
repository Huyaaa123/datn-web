<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryDetailController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MyOrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserInformationController;
use App\Http\Controllers\VoucherClientController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth', IsAdmin::class])->name('admin.index');
Route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware(['auth', IsAdmin::class])->name('admin.dashboard');

Route::prefix('admin')->name('admin.')->middleware(['auth', IsAdmin::class])->group(function () {

    Route::resource('category', CategoryController::class);

    Route::resource('product', ProductController::class);

    Route::resource('orders', OrderController::class);

    Route::resource('users', AdminUserController::class);

    Route::resource('vouchers', VoucherController::class);
});


Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::get('/product/{slug}', [ProductDetailController::class, 'show'])->name('product.show');

Route::get('/', [ProductDetailController::class, 'index'])->name('product.index');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/cong', [CartController::class, 'cong'])->name('cart.cong');
Route::post('/cart/tru', [CartController::class, 'tru'])->name('cart.tru');


Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/online_checkout', [CheckoutController::class, 'online_checkout'])->name('checkout.online_checkout');


// thong tin user
Route::resource('user', UserController::class);

//dia chi user
Route::get('/addresses/user', [UserInformationController::class, 'address'])->name('addresses.index');
Route::post('/addresses/user', [UserInformationController::class, 'addstore'])->name('addresses.store');
Route::delete('/addresses/{id}', [UserInformationController::class, 'destroy'])->name('addresses.destroy');

//cap nhat mk user
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password/user', [UserInformationController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password/user', [UserInformationController::class, 'changePassword'])->name('password.update');
    Route::get('/order', [MyOrderController::class, 'index'])->name('order.client.user');
    Route::get('/order{id}', [MyOrderController::class, 'edit'])->name('order.client.show');
    Route::put('/order/{id}/update', [MyOrderController::class, 'update'])->name('order.client.update');
});
//menu

Route::get('/categories/{slug}', [CategoryDetailController::class, 'show'])->name('index.show');
Route::get('/categories/{slug}', [CategoryDetailController::class, 'view'])->name('index.view');

Route::get('/vouchers', [VoucherClientController::class, 'index'])->name('index.vouchers');
Route::post('/cart/apply-voucher', [VoucherClientController::class, 'applyVoucher'])->name('cart.applyVoucher');




Route::get('/thanks', [PaymentController::class, 'thankYou'])->name('order.success');

