<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

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
// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/carbontest', 'HomeController@carbontest')->name('test'); // read
Route::get('/test/{allocation_id}', 'AllocationController@saveAllocatedProductsToInventory')->name('tesct'); // read

Route::get('/destructiontest', 'HomeController@destructiontest')->name('destructiontest'); // read

Route::get('/home', 'HomeController@index')->name('home'); // read

Route::get('/request-forms', function () {
    return view('request-forms.request-forms');
});

Route::get('/dashboard', function () {
    return view('home');
});


Route::get('/users/create-user', 'UserController@createUserView')->name('create-user'); // read
Route::get('/users', 'UserController@index')->name('all-users'); // read
Route::post('/users/create/submit', 'UserController@saveUser')->name('create-user-submit'); // submit
Route::get('/users/{id}/edit/', 'UserController@show')->name('edit-user-view'); // read single user
Route::put('/users/{id}/edit/submit', 'UserController@edit')->name('edit-user-submit'); // submit edit user

//Allocations
Route::get('/data/allocations', 'AllocationController@index')->name('all-allocations');
Route::get('/data/create-allocation', function () {
    return view('data-allocations-create');
}); // CREATE View

Route::post('/data/allocation/create/submit', 'AllocationController@store')->name('create-allocation-submit'); //CREATE Submit Route
Route::get('/data/allocation/{id}/edit/', 'AllocationController@show')->name('edit-allocation-view'); // EDIT View
Route::put('/data/allocation/{id}/edit/submit', 'AllocationController@edit')->name('edit-allocation-submit'); // EDIT Submit Route

//Companies
Route::get('/users/companies', 'MemberController@index')->name('all-companies');  // Show All Companies
Route::get('/users/create-company', 'MemberController@createCompanyView')->name('create-company');  // Show All Companies
Route::post('/users/member/create/submit', 'MemberController@store')->name('create-member-submit'); //CREATE Submit Route 
Route::get('/users/companies/{id}/edit/', 'MemberController@show')->name('edit-company-view'); // EDIT View
Route::put('/users/companies/{id}/edit/submit', 'MemberController@edit')->name('edit-company-submit'); // EDIT Submit Route

//Beneficiary
Route::get('/users/beneficiaries', 'BeneficiaryController@beneficiaryList')->name('beneficiary-list');
Route::get('/users/beneficiaries/{id}/edit', 'BeneficiaryController@editBeneficiary')->name('beneficiary-edit');
Route::get('/users/beneficiaries/create', 'BeneficiaryController@createBeneficiary')->name('beneficiary-create');
Route::post('/users/beneficiaries/create/submit', 'BeneficiaryController@createBeneficiarySubmit')->name('beneficiary-create-submit');
Route::post('/users/beneficiaries/{id}/edit/submit', 'BeneficiaryController@editBeneficiarySubmit')->name('beneficiary-edit-submit');

//cfs
Route::get('/call-for-support', 'CfsPostController@index')->name('call-for-support');
Route::get('/call-for-support/create', 'CfsPostController@create')->name('call-for-support-create');
Route::post('/call-for-support/create/submit', 'CfsPostController@store')->name('call-for-support-submit');
Route::get('call-for-support/{id}/edit', 'CfsPostController@show')->name('edit-call-for-support-view');
Route::put('/call-for-support/{id}/edit/submit', 'CfsPostController@edit')->name('call-for-support-submit-edit');

// Route::post('/send-notification', 'CfsPostController@sendNotification')->name('send-notification');

//nods
Route::get('/nods', 'NodPostController@index')->name('nod');
Route::get('/nods/{id}/form', 'NodPostController@show')->name('nod-form-view');
Route::get('/nods/{id}/form/{itemId}/item', 'NodPostController@showItem')->name('nod-form-view-item');
Route::put('/nods/{id}/form/item/submit', 'NodPostController@updateItem')->name('nods-view-item-submit-edit');
Route::get('/nods/{id}/form/change-approval/{approvalCode}', 'NodPostController@updateApproval')->name('nods-change-approval-status');

// PRODUCT DONATION
Route::get('/product-donation/initial-details', 'ProductDonationController@initialDetailsView')->name('pd-initial-details');
Route::get('/product-donation/initial-details/{id}', 'ProductDonationController@initialDetailsViewRead')->name('pd-initial-details-read');
Route::delete('/product-donation/contribution/delete', 'ProductDonationController@cancelContribution')->name('contribution-delete');

Route::post('/product-donation/initial-details/save', 'ProductDonationController@saveInitialDetails')->name('pd-initial-details-save');

Route::get('/product-donation/{contribution_id}/donations/{contribution_no}', 'ProductDonationController@donationsView')->name('pd-donations');
Route::get('/product-donation/{contribution_id}/secondary-details', 'ProductDonationController@secondaryDetailsView')->name('pd-secondary-details');
Route::post('/product-donation/{contribution_id}/secondary-details/save', 'ProductDonationController@saveSecondaryDetails')->name('pd-secondary-details-save');
Route::get('/product-donation/{donation_id}/edit-donation/', 'ProductDonationController@updateDonationsView')->name('pd-donation-update-view');
Route::post('/product-donation/{donation_id}/update-donation-drafts', 'ProductDonationController@updateDonationToDrafts')->name('pd-donation-update-to-drafts');
Route::get('/product-donation/finish', 'ProductDonationController@finishView')->name('pd-finish');

// CONTRIBUTIONS
Route::get('/contributions', 'ContributionController@ContributionList')->name('contribution-list');
Route::get('/contributions/{contribution_id}/details', 'ContributionController@ContributionDetails')->name('contribution-details');
Route::get('/contributions/{donation_id}/edit-donation/', 'ContributionController@updateDonationsView')->name('contribution-donation-edit-view');
Route::post('/contributions/{donation_id}/update-donation', 'ContributionController@updateDonation')->name('contribution-donation-update');

Route::post('/contributions/{contribution_id}/update-status', 'ContributionController@updateContributionStatus')->name('contribution-status-update');
Route::post('/contributions/{contribution_id}/cancel-donation-status', 'ContributionController@cancelDonation')->name('cancel-donation-status');
Route::get('/contributions/{id}/download-pdf', 'ContributionController@downloadDIDRFForm')->name('download-pdf');

// ALLOCATIONS
Route::get('/allocations', 'AllocationController@allocationList')->name('allocation-list');
Route::get('/allocations/{allocation_id}/details', 'AllocationController@allocationDetails')->name('allocation-details');
Route::get('/allocations/create', 'AllocationController@allocationCreate')->name('allocation-create');
Route::post('/allocations/create/save', 'AllocationController@saveAllocation')->name('allocation-create-save');
Route::get('/allocations/{allocation_id}/add-products', 'AllocationController@allocationAddProducts')->name('allocation-add-products');
Route::post('/allocations/{allocation_id}/update-status', 'AllocationController@updateAllocationStatus')->name('allocation-status-update');
Route::get('/allocations/{allocated_product_id}/edit-allocated-product', 'AllocationController@editAllocatedProduct')->name('allocation-edit-view');
Route::post('/allocations/cancel-allocated-product', 'AllocationController@cancelAllocatedProduct')->name('allocation-cancel-product');
Route::post('/allocations/{allocated_product_id}/update-allocated-product', 'AllocationController@revertAllocatedProduct')->name('allocation-update-product');

Route::get('/allocations/create/{id}', 'AllocationController@allocationCreateRead')->name('allocation-create-read');
Route::delete('/allocations/delete', 'AllocationController@cancelAllocation')->name('allocation-delete');

// PRODUCT DESTRUCTIONS
Route::get('/product-destruction', 'ProductDestructionController@productDestructionList')->name('product-destruction-list');
Route::get('/product-destruction/{destruction_id}/details', 'ProductDestructionController@productDestructionDetails')->name('product-destruction-details');
Route::get('/product-destruction/create', 'ProductDestructionController@productDestructionCreate')->name('product-destruction-create');
Route::post('/product-destruction/create/save', 'ProductDestructionController@saveDestruction')->name('destruction-create-save');
Route::get('/product-destruction/{destruction_id}/add-products', 'ProductDestructionController@productDestructionAddProducts')->name('product-destruction-add-products');
Route::post('/product-destruction/{destruction_id}/update-status', 'ProductDestructionController@updateDestructionStatus')->name('destruction-status-update');
Route::get('/product-destruction/{destructed_product_id}/edit-destructed-product', 'ProductDestructionController@editDestructedProduct')->name('destruction-edit-view');
Route::post('/product-destruction/cancel-destructed-product', 'ProductDestructionController@cancelDestructedProduct')->name('destruction-cancel-product');
Route::post('/product-destruction/{destructed_product_id}/update-destructed-product', 'ProductDestructionController@revertDestructedProduct')->name('destruction-update-product');

Route::get('/product-destruction/create/{id}', 'ProductDestructionController@productDestructionCreateRead')->name('product-destruction-create-read');
Route::delete('/product-destruction/destruction/delete', 'ProductDestructionController@cancelDestruction')->name('destruction-delete');

// destruction-edit-view
// INVENTORY
Route::get('/inventory', 'InventoryController@inventoryList')->name('inventory-list');
Route::get('/inventory/{id}/edit', 'InventoryController@inventoryDetails')->name('inventory-edit');
Route::post('/inventory/{inventory_id}/update', 'InventoryController@inventoryUpdate')->name('inventory-update');

// REPORTS
Route::get('/reports', 'ReportsController@reportsList')->name('reports-list');
Route::get('/reports/filter', 'ReportsController@filteredReportsList')->name('filter-reports-list');
Route::get('/reports/generate/', 'ReportsController@generateExcelReport')->name('reports-generate');


Route::get('/invoice', function () {
    // $pdf = PDF::loadView('invoice');
    // $pdf->setPaper('A4', 'landscape');
    // return $pdf->download('invoice.pdf');
    return view('invoice');
});

Route::get('/invoice-download', function (){

    // $options = new Options();
    // $options->setIsRemoteEnabled(true);
    
    // $domPdf->setOptions($options);
    // $domPdf->output();
    $pdf = PDF::loadView('invoice');
    $pdf->setPaper('A4', 'landscape');

    return $pdf->download('invoice.pdf');
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/testpdf', 'ContributionController@test')->name('test-dpf');

Auth::routes();
