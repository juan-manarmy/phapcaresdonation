<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->post('/register','AuthController@register');
Route::middleware('api')->post('/login','AuthController@login');

Route::group(['middleware'=>'auth:sanctum'], function() {
    Route::get('/cfs-posts','CfsPostControllerApi@index');
    Route::get('/cfs-posts/spinner','CfsPostControllerApi@getPostSpinner');
    Route::get('/cfs-posts/{id}','CfsPostControllerApi@getPostById');
    Route::get('/cfs-posts/{id}/cfs-requests','CfsPostControllerApi@getPostRequests');
    Route::get('/cfs-posts/{id}/cfs-donors','CfsPostControllerApi@getPostDonors');
    Route::get('/cfs-post/get-events-selection','CfsPostControllerApi@getEventSelection');
    Route::get('/user-profile','UserControllerApi@show');
    Route::put('/user/update-token','UserControllerApi@updateDeviceToken');
    Route::get('/articles','ArticleControllerApi@index');
    Route::get('/articles/{id}','ArticleControllerApi@show');
    Route::get('/donated-cfs-posts','CfsPostControllerApi@checkDonation');
    Route::get('/donated/{cfs_post_id}/{donation_type}','DonationControllerApi@show');
    Route::get('/beneficiaries/{id}','DonationControllerApi@getBeneficiaries');
    Route::get('/search/{query}','CfsPostControllerApi@search');
    Route::get('/post','PostControllerApi@show');
    Route::post('/contributions/save-initial-details','ContributionControllerApi@saveInitialDetails');
    Route::post('/contributions/{contribution_id}/save-medicine-donation','ContributionControllerApi@saveMedicineDonation');
    Route::post('/contributions/{contribution_id}/save-promats-donation','ContributionControllerApi@savePromatsDonation');
    Route::post('/contributions/{contribution_id}/save-monetary-donation','ContributionControllerApi@saveMonetaryDonation');
    Route::put('/contributions/{contribution_id}/save-total-donation','ContributionControllerApi@saveTotalDonation');
    Route::put('/contributions/{id}/update-medicine-donation','ContributionControllerApi@updateMedicineDonation');
    Route::put('/contributions/{id}/update-promats-donation','ContributionControllerApi@updatePromatsDonation');
    Route::post('/contributions/{id}/update-monetary-donation','ContributionControllerApi@updateMonetaryDonation');
    Route::post('/contributions/{contribution_id}/save-secondary-details','ContributionControllerApi@saveSecondaryDetails');
    Route::get('/contributions/get-contributions','ContributionControllerApi@getContributions');
    Route::get('/contributions/{contribution_id}/get-approved-contribution','ContributionControllerApi@getApprovedContribution');
    Route::get('/contributions/{id}/get-contribution-by-id','ContributionControllerApi@getContributionById');
    Route::get('/contributions/get-contributions-drafts','ContributionControllerApi@getContributionsDrafts');
    Route::get('/contributions/get-contributions-drafts-count','ContributionControllerApi@getContributionsDraftsCount');
    Route::put('/contributions/{id}/update-initial-details','ContributionControllerApi@updateInitialDetails');
    Route::get('/contributions/{contribution_id}/get-contribution-donations','ContributionControllerApi@getContributionDonation');
    Route::get('/contributions/{contribution_id}/get-medicine-donations','ContributionControllerApi@getMedicineDonation');
    Route::get('/contributions/{contribution_id}/get-promats-donations','ContributionControllerApi@getPromatsDonation');
    Route::get('/contributions/{contribution_id}/get-monetary-donations','ContributionControllerApi@getMonetaryDonation');
    Route::get('/contributions/{contribution_id}/get-donations','ContributionControllerApi@getDonations');
    Route::delete('/contributions/{id}/delete-donation','ContributionControllerApi@deleteDonation');
    Route::get('/contributions/{contribution_id}/get-total-donations','ContributionControllerApi@getTotalDonations');
    Route::get('/contributions/{contribution_id}/get-documents','DocumentControllerApi@getDocuments');
    Route::get('/inventory/get-inventory','InventoryControllerApi@getInventory');
    Route::delete('/contributions/{id}/delete-contribution','ContributionControllerApi@deleteContribution');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});