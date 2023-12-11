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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::middleware('api')->post('/product-donation/{contribution_id}/save-donation','ProductDonationController@saveDonation');
Route::middleware('api')->get('/product-donation/{contribution_id}/show-donations','ProductDonationController@showDonations');
Route::middleware('api')->delete('/product-donation/{id}/delete-donation','ProductDonationController@deleteDonation');
Route::middleware('api')->post('/product-donation/{contribution_id}/save-total-donations','ProductDonationController@saveTotalDonations');
Route::middleware('api')->post('/upload-monetary','ProductDonationController@uploadMonetary');

Route::middleware('api')->get('/members/show-members','AllocationController@getMembers');
Route::middleware('api')->get('/inventory/{member_id}/{allocation_id}/show-inventory','AllocationController@getInventory');
Route::middleware('api')->get('/inventory/{product_id}/show-product','AllocationController@getSelectedProduct');
Route::middleware('api')->post('/allocation/{allocation_id}/save-allocated-product','AllocationController@saveAllocatedProduct');
Route::middleware('api')->get('/allocation/{allocation_id}/get-allocated-product','AllocationController@getAllocatedProduct');
Route::middleware('api')->delete('/allocation/{allocated_product_id}/delete-allocated-product','AllocationController@processAllocatedProductsRemove');
Route::middleware('api')->post('/allocation/{allocation_id}/save-total-donations','AllocationController@saveTotalDonations');

Route::middleware('api')->get('/destruction/{destruction_id}/get-destructed-product','ProductDestructionController@getDestructedProduct');
Route::middleware('api')->post('/destruction/{destruction_id}/save-total-donations','ProductDestructionController@saveTotalDonations');
Route::middleware('api')->post('/destruction/{destruction_id}/save-destructed-product','ProductDestructionController@saveDestructedProduct');
Route::middleware('api')->get('/destruction/inventory/{member_id}/{destruction_id}/show-inventory','ProductDestructionController@getInventory');
Route::middleware('api')->delete('/destruction/{destruction_id}/delete-destructed-product','ProductDestructionController@processDestructedProductsRemove');
