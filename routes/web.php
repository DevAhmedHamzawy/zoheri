<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\HurdleController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SellingController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('checkifuseractive');


/*------- forget password ------*/
Route::get('forget_password', [ForgetPasswordController::class, 'index'])->name('forget_password');

Route::post('mail', [ForgetPasswordController::class, 'mail'])->name('mail');

Route::post('check_otp', [ForgetPasswordController::class, 'checkOtp'])->name('check_otp');

Route::post('reset_password', [ForgetPasswordController::class, 'resetPassword'])->name('reset_password');
/*------- forget password ------*/


Route::group(['middleware' => ['auth','checkifuseractive','checkifroleactive']],function(){

        // Users
        Route::resource('users', UserController::class);
        Route::get('users/{user}/delete', [UserController::class, 'destroy'])->name('users.delete');
        Route::get('users/{user}/active', [UserController::class, 'active'])->name('users.active');
        Route::get('users/{user}/deactive', [UserController::class, 'deactive'])->name('users.deactive');
        Route::get('trash_users', [UserController::class, 'trash'])->name('users.trash');
        Route::get('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

        // roles
        Route::resource('roles', RoleController::class);
        Route::get('roles/{role}/delete', [RoleController::class, 'destroy'])->name('roles.delete');
        Route::get('roles/{role}/active', [RoleController::class, 'active'])->name('roles.active');
        Route::get('roles/{role}/deactive', [RoleController::class, 'deactive'])->name('roles.deactive');
        Route::get('trash_roles', [RoleController::class, 'trash'])->name('roles.trash');
        Route::get('roles/{id}/restore', [RoleController::class, 'restore'])->name('roles.restore');

        // hurtles
        Route::resource('hurdles', HurdleController::class);
        Route::get('hurdles/{hurdle}/delete', [HurdleController::class, 'destroy'])->name('hurdles.delete');
        Route::get('hurdles/{hurdle}/active', [HurdleController::class, 'active'])->name('hurdles.active');
        Route::get('hurdles/{hurdle}/deactive', [HurdleController::class, 'deactive'])->name('hurdles.deactive');
        Route::get('trash_hurdles', [HurdleController::class, 'trash'])->name('hurdles.trash');
        Route::get('hurdles/{id}/restore', [HurdleController::class, 'restore'])->name('hurdles.restore');

        // vehicles
        Route::resource('vehicles', VehicleController::class);
        Route::get('vehicles/{vehicle}/delete', [VehicleController::class, 'destroy'])->name('vehicles.delete');
        Route::get('vehicles/{vehicle}/active', [VehicleController::class, 'active'])->name('vehicles.active');
        Route::get('vehicles/{vehicle}/deactive', [VehicleController::class, 'deactive'])->name('vehicles.deactive');
        Route::get('trash_vehicles', [VehicleController::class, 'trash'])->name('vehicles.trash');
        Route::get('vehicles/{id}/restore', [VehicleController::class, 'restore'])->name('vehicles.restore');

        // models
        Route::resource('models', ModelController::class);
        Route::get('models/{model}/delete', [ModelController::class, 'destroy'])->name('models.delete');
        Route::get('models/{model}/active', [ModelController::class, 'active'])->name('models.active');
        Route::get('models/{model}/deactive', [ModelController::class, 'deactive'])->name('models.deactive');
        Route::get('trash_models', [ModelController::class, 'trash'])->name('models.trash');
        Route::get('models/{id}/restore', [ModelController::class, 'restore'])->name('models.restore');

        // products
        Route::resource('products', ProductController::class);
        Route::get('products/{product}/delete', [ProductController::class, 'destroy'])->name('products.delete');
        Route::get('products/{product}/active', [ProductController::class, 'active'])->name('products.active');
        Route::get('products/{product}/deactive', [ProductController::class, 'deactive'])->name('products.deactive');
        Route::get('trash_products', [ProductController::class, 'trash'])->name('products.trash');
        Route::get('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');

        // terms
        Route::resource('terms', TermController::class);
        Route::get('terms/{term}/delete', [TermController::class, 'destroy'])->name('terms.delete');
        Route::get('terms/{term}/active', [TermController::class, 'active'])->name('terms.active');
        Route::get('terms/{term}/deactive', [TermController::class, 'deactive'])->name('terms.deactive');
        Route::get('trash_terms', [TermController::class, 'trash'])->name('terms.trash');
        Route::get('terms/{id}/restore', [TermController::class, 'restore'])->name('terms.restore');


        // branches
        Route::resource('branches', BranchController::class);
        Route::get('branches/{branch}/delete', [BranchController::class, 'destroy'])->name('branches.delete');
        Route::get('branches/{branch}/active', [BranchController::class, 'active'])->name('branches.active');
        Route::get('branches/{branch}/deactive', [BranchController::class, 'deactive'])->name('branches.deactive');
        Route::get('trash_branches', [BranchController::class, 'trash'])->name('branches.trash');
        Route::get('branches/{id}/restore', [BranchController::class, 'restore'])->name('branches.restore');

        // expenses
        Route::resource('expenses', ExpenseController::class);
        Route::get('expenses/{expense}/delete', [ExpenseController::class, 'destroy'])->name('expenses.delete');
        Route::get('expenses/{expense}/active', [ExpenseController::class, 'active'])->name('expenses.active');
        Route::get('expenses/{expense}/deactive', [ExpenseController::class, 'deactive'])->name('expenses.deactive');
        Route::get('trash_expenses', [ExpenseController::class, 'trash'])->name('expenses.trash');
        Route::get('expenses/{id}/restore', [ExpenseController::class, 'restore'])->name('expenses.restore');

        // code
        Route::resource('code', SettingController::class)->only(['edit', 'update']);
        Route::resource('settings', SettingController::class)->only(['edit', 'update']);

        // orders
        Route::resource('sellings', SellingController::class);
        Route::get('sellings/{selling}/delete', [SellingController::class, 'destroy'])->name('sellings.delete');
        Route::get('sellings/{selling}/invoice', [SellingController::class, 'showInvoice'])->name('sellings.invoice');
        Route::get('sellings/{selling}/unique_card', [SellingController::class, 'showUniqueCard'])->name('sellings.unique_card');

        // discounts
        Route::resource('discounts', DiscountController::class);
        Route::get('discounts/{discount}/delete', [DiscountController::class, 'destroy'])->name('discounts.delete');
        Route::get('discounts/{discount}/active', [DiscountController::class, 'active'])->name('discounts.active');
        Route::get('discounts/{discount}/deactive', [DiscountController::class, 'deactive'])->name('discounts.deactive');
        Route::get('trash_discounts', [DiscountController::class, 'trash'])->name('discounts.trash');
        Route::get('discounts/{id}/restore', [DiscountController::class, 'restore'])->name('discounts.restore');

        // get product details from data
        Route::get('get_product_data', [ProductController::class, 'getData']);

        // get discount amounts from type
        Route::get('get_discount_amounts/{discount_sort}' , [DiscountController::class, 'discountAmount']);

        // get product code
        Route::get('get_product_code/{vehicle_id}/{hurdle_id}/{model_id}', [ProductController::class, 'getProductCode']);
});

Route::get('/optimize-clear', function() {
    $exitCode = Artisan::call('optimize:clear');
    return 'Application cache cleared';
});


Route::get('/storage-link', function () {
    Artisan::call('storage:link');
});
