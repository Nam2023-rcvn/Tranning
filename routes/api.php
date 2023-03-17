<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('oauth')->group(function () {
    Route::post('login', Auth\Login::class);
});

Route::prefix('users')->middleware(['auth:api'])->group(function () {
    Route::get('/', User\GetUserList::class);
    Route::post('/', User\CreateUser::class);
    Route::get('/{id}', User\GetUser::class);
    Route::put('/{id}', User\UpdateUser::class);
    Route::delete('/{id}', User\DeleteUser::class);
    Route::put('/{id}/block', User\BlockUser::class);
});

Route::prefix('customers')->middleware(['auth:api'])->group(function () {
    Route::get('/', Customer\GetCustomerList::class);
    Route::post('/', Customer\CreateCustomer::class);
    Route::put('/{id}', Customer\UpdateCustomer::class);
    Route::get('/export', Customer\ExportCustomer::class);
    Route::post('/import', Customer\ImportCustomer::class);
});

Route::prefix('products')->middleware(['auth:api'])->group(function () {
    Route::get('/', Product\GetProductList::class);
    Route::post('/', Product\CreateProduct::class);
    Route::post('/{id}', Product\UpdateProduct::class);
    Route::delete('/{id}', Product\DeleteProduct::class);
});
