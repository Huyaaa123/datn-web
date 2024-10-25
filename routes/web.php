<?php

<<<<<<< Updated upstream
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CategoryController;
=======
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryDetailController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
>>>>>>> Stashed changes
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

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::resource('category', CategoryController::class);

    Route::resource('product', ProductController::class);

    Route::resource('orders', OrderController::class);

});

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::get('/product/{slug}', [ProductDetailController::class, 'show'])->name('product.show');

Route::get('/', [ProductDetailController::class, 'index'])->name('product.index');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/remove/all', [CartController::class, 'removeAll'])->name('cart.remove.all');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/online_checkout', [CheckoutController::class, 'online_checkout'])->name('checkout.online_checkout');


Route::get('/user', [UserController::class, 'index'])->name('user.index');

//menu

Route::get('/categories/{slug}', [CategoryDetailController::class, 'show'])->name('index.show');
Route::get('/categories/{slug}', [CategoryDetailController::class, 'view'])->name('index.view');

//cong tt


