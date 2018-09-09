<?php

use Illuminate\Http\Request;

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


/*
* Buyer
*/
Route::apiResource('buyers','API\Buyer\BuyerController',['only'=>['index','show']]);

/*
* Seller
*/
Route::apiResource('sellers','API\Seller\SellerController',['only'=>['index','show']]);

/*
* Product
*/
Route::apiResource('products','API\Product\ProductController',['only'=>['index','show']]);

/*
* Category
*/
Route::apiResource('categories','API\Category\CategoryController',['except'=>['create','edit']]);

/*
* Transaction
*/
Route::apiResource('transactions','API\Transaction\TransactionController',['only'=>['index','show']]);

/*
* User
*/
Route::apiResource('users','API\User\UserController',['except'=>['create','edit']]);












