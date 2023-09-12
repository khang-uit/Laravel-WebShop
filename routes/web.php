<?php

use App\Http\Controllers\Admin\CartController as AdminCartController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\MainController as AdminMainController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\Users\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Services\UploadService;
use Illuminate\Support\Facades\Route;

Route::get('admin/users/login',
    [LoginController::class, 'index']
)->name('login');

Route::post('admin/users/login/store',
    [LoginController::class, 'store']
);

Route::post('admin/users/assign-roles',
    [LoginController::class, 'assign_roles']
)->name('assign-roles');

Route::get('admin/users/register-auth', [LoginController::class, 'registerAuth'])->name('registerpage');
Route::post('admin/register', [LoginController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function(){

    Route::prefix('admin')->group(function(){
        Route::get('/',
            [AdminMainController::class, 'index']
        )->name('admin');

        Route::get('/logout',
            [LoginController::class, 'logout']
        )->name('admin/logout');

        Route::get('main',
            [AdminMainController::class, 'index']
        );
        Route::post('filter-by-date',
            [AdminMainController::class, 'filterByDate']
        );
        Route::post('days-order',
            [AdminMainController::class, 'daysOrder']
        );

        Route::group(['middleware' => 'auth.roles:admin,author',], function(){
            #Menu
            Route::prefix('menu')->group(function(){
                Route::get('add',
                    [MenuController::class, 'create']
                );
                Route::post('add',
                    [MenuController::class, 'store']
                );
                Route::get('list',
                    [MenuController::class, 'index']
                );
                Route::get('edit/{menu}',
                    [MenuController::class, 'show']
                );
                Route::post('edit/{menu}',
                    [MenuController::class, 'update']
                );
                Route::DELETE('destroy',
                    [MenuController::class, 'destroy']
                );
            });

                #Product       
            Route::prefix('product')->group(function () {
                Route::get('add', [ProductController::class, 'create']);
                Route::post('add', [ProductController::class, 'store']);
                Route::get('list', [ProductController::class, 'index']);
                Route::get('edit/{product}', [ProductController::class, 'show']);
                Route::post('edit/{product}', [ProductController::class, 'update']);
                Route::DELETE('destroy',
                    [ProductController::class, 'destroy']
                );
            });

            #Slider
            Route::prefix('slider')->group(function () {
                Route::get('add', [SliderController::class, 'create']);
                Route::post('add', [SliderController::class, 'store']);
                Route::get('list', [SliderController::class, 'index']);
                Route::get('edit/{slider}', [SliderController::class, 'show']);
                Route::post('edit/{slider}', [SliderController::class, 'update']);
                Route::DELETE('destroy',
                    [SliderController::class, 'destroy']
                );
            });

            #Store
            Route::prefix('store')->group(function(){
                Route::get('add',
                    [StoreController::class, 'create']
                );
                Route::post('add',
                    [StoreController::class, 'store']
                );
                Route::get('list',
                    [StoreController::class, 'index']
                );
                // Route::get('edit/{menu}',
                //     [StoreController::class, 'show']
                // );
                // Route::post('edit/{menu}',
                //     [StoreController::class, 'update']
                // );
                // Route::DELETE('destroy',
                //     [StoreController::class, 'destroy']
                // );
            });
        });
       

        
        Route::group(['middleware' => 'auth.roles:admin,user',], function(){
            #Upload

            Route::get('customers', [AdminCartController::class, 'index']);
            Route::get('customers/view/{customer}', [\App\Http\Controllers\Admin\CartController::class, 'show']);
            Route::get('customers/view/print-order/{checkout_code}', [App\Http\Controllers\Admin\CartController::class, 'printOrder']);
        });

        Route::group(['middleware' => 'auth.roles:admin'], function(){
            #User
            Route::prefix('users')->group(function(){
                Route::get('list',
                    [LoginController::class, 'list']
                );
                Route::get('delete/{id}',
                    [LoginController::class, 'delete']
                );
                // Route::post('add',
                //     [LoginController::class, 'store']
                // );
                // // Route::get('list',
                // //     [LoginController::class, 'index']
                // // );
                // Route::get('edit/{menu}',
                //     [LoginController::class, 'show']
                // );
                // Route::post('edit/{menu}',
                //     [LoginController::class, 'update']
                // );
                // Route::DELETE('destroy',
                //     [LoginController::class, 'destroy']
                // );
            });
        });

        

        Route::post('upload/services', [UploadController::class, 'store']);

        
    });

    
    
});

Route::get('/', [App\Http\Controllers\MainController::class, 'index']);
Route::get('search', [App\Http\Controllers\MainController::class, 'search'])->name('search');
Route::post('/services/load-product', [App\Http\Controllers\MainController::class, 'loadProduct']);

Route::get('danh-muc/{id}-{slug}.html', [App\Http\Controllers\MenuController::class, 'index']);
Route::post('danh-muc/auto-complete', [App\Http\Controllers\MenuController::class, 'autoComplete']);

Route::get('san-pham/{id}-{slug}.html', [App\Http\Controllers\ProductController::class, 'index']);

Route::post('add-cart', [App\Http\Controllers\CartController::class, 'index']);
Route::get('carts', [App\Http\Controllers\CartController::class, 'show']);
Route::post('update-cart', [App\Http\Controllers\CartController::class, 'update']);
Route::get('carts/delete/{id}', [App\Http\Controllers\CartController::class, 'remove']);
Route::post('carts', [App\Http\Controllers\CartController::class, 'addCart']);
Route::get('map', [App\Http\Controllers\CartController::class, 'map']);
Route::post('vnpay_payment', [App\Http\Controllers\CartController::class, 'vnpay_payment'])->name('vnpay_payment');
Route::get('donepayment', [App\Http\Controllers\CartController::class, 'done_payment'])->name('done_payment');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('nearest-dashboard', [DashboardController::class, 'nearestDataDashboard'])->name('nearest-dashboard');
Route::post('addreview', [App\Http\Controllers\ReviewController::class, 'addreview'])->name('addreview');









