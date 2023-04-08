<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//to create Product with datas provided
Route::post('/createProduct',[ProductController::class, "createProduct"]); 

//to read the record
Route::get('/readProduct',[ProductController::class, "readProduct"]);

//to update the record
Route::post('/updateProduct',[ProductController::class, "updateProduct"]);

//to delete the record
Route::post('/deleteProduct',[ProductController::class, "deleteProduct"]);
