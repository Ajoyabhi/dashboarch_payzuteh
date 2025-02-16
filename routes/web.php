<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\Login;
use App\Http\Controllers\Users;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Staff;
use App\Http\Controllers\Agent;
use App\Http\Controllers\PayoutUsers;

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

Route::get('/docs', function () {
    return view('welcome');
});

Route::get('/', [Login::class, 'index']);

Route::get('/login', [Login::class, 'index'])->name('login');
Route::post('/authentication', [Login::class, 'verifyUserAuth']);
Route::get('/logout', [Login::class, 'logout'])->name('logout');

Route::get('/add-user', [Users::class, 'create'])->name('addUser');
Route::post('/create-user', [Users::class, 'store']);

Route::get('/payment-details', [Login::class, 'showPayment'])->name('payment-details');

// Route::middleware('auth')->group(function () {

//     Route::get('/admin/dashboard', [Admin::class, 'index']);
//     Route::match(['get', 'post'], '/admin/change-password', [Admin::class, 'changePassword'])->name('change-password');
//     Route::get('/admin/view-profile', [Admin::class, 'viewProfile']);
//     Route::get('/admin/manage-users', [Admin::class, 'manageUsers'])->name('manage-users');
//     Route::match(['get', 'post'], '/admin/add-user', [Admin::class, 'addUsers'])->name('add-user');
//     Route::get('/admin/edit-user/{user}', [Admin::class, 'editUser'])->name('edit-user');
//     Route::put('/admin/update-user/{user}', [Admin::class, 'updateUser'])->name('update-user');

//     Route::get('/admin/manage-staff', [Admin::class, 'manageStaff'])->name('manage-staff');
//     Route::match(['get', 'post'], '/admin/add-staff', [Admin::class, 'addStaff'])->name('add-staff');
//     Route::get('/admin/edit-staff/{user}', [Admin::class, 'editStaff'])->name('edit-staff');
//     Route::put('/admin/update-staff/{user}', [Admin::class, 'updateStaff'])->name('update-staff');
    
//     Route::get('/admin/manage-agent', [Admin::class, 'manageAgent'])->name('manage-agent');
//     Route::match(['get', 'post'], '/admin/add-agent', [Admin::class, 'addAgent'])->name('add-agent');
    
//     Route::post('/admin/update-users-status', [Admin::class, 'manageUsersStatus'])->name('manage-users-status');
//     Route::post('/admin/reset-users-password', [Admin::class, 'resetUserPassword'])->name('reset-users-password');
//     Route::match(['get', 'post'], '/admin/manage-user-charge/{id?}', [Admin::class, 'manageUserCharge'])->name('/admin/manage-user-charge/{id?}');
//     Route::post('/admin/save-user-charge', [Admin::class, 'saveUserCharge'])->name('/admin/save-user-charge');
//     Route::post('/admin/save-user-ip', [Admin::class, 'saveUserIP'])->name('/admin/save-user-ip');
//     Route::post('/admin/save-user-platform-charge', [Admin::class, 'saveUserPlatformCharge'])->name('/admin/save-user-platform-charge');
//     Route::post('/admin/save-user-setting', [Admin::class, 'saveUserSetting'])->name('/admin/save-user-setting');
//     Route::post('/admin/update-user-fund', [Admin::class, 'updateUserFund'])->name('/admin/update-user-fund');
//     Route::get('/admin/wallet-report', [Admin::class, 'walletReport'])->name('admin/wallet-report');
//     Route::match(['get', 'post'], '/admin/wallet-report-data', [Admin::class, 'walletReportData'])->name('/admin/wallet-report-data');
//     Route::get('/admin/payout-report', [Admin::class, 'payoutReport'])->name('admin/payout-report');
//     Route::match(['get', 'post'], '/admin/payout-report-data', [Admin::class, 'payoutReportData'])->name('/admin/payout-report-data');
//     Route::match(['get', 'post'], '/admin/wallet-topup', [Admin::class, 'WalletTopup'])->name('/admin/wallet-topup');
//     Route::match(['get', 'post'], '/admin/wallet-topup-request', [Admin::class, 'WalletTopupRequest'])->name('/admin/wallet-topup-request');
//     Route::match(['get', 'post'], '/admin/wallet-topup-search', [Admin::class, 'WalletTopupSearch'])->name('/admin/wallet-topup-search');
//     Route::match(['get', 'post'], '/admin/wallet-topup-report', [Admin::class, 'WalletTopupReport'])->name('/admin/wallet-topup-report');
//     Route::match(['get', 'post'], '/admin/manage-staff-ip/{id?}', [Admin::class, 'manageStaffIp'])->name('/admin/manage-staff-ip/{id?}');
//     Route::match(['get', 'post'], '/admin/save-satff-ip', [Admin::class, 'addStaffIp'])->name('/admin/save-satff-ip');
//     Route::match(['get', 'post'], '/admin/deduct-chargeback', [Admin::class, 'deductChargeback'])->name('/admin/deduct-chargeback');

//     Route::get('/admin/deduct-chargeback', [Admin::class, 'deductChargeback'])->name('admin/deduct-chargeback');
//     Route::get('/admin/chargeback-report', [Admin::class, 'chargebackReport'])->name('admin/chargeback-report');
//     Route::match(['get', 'post'], '/admin/chargeback-report-data', [Admin::class, 'chargebackReportData'])->name('/admin/chargeback-report-data');

//     Route::get('/admin/payin-report', [Admin::class, 'payinReport'])->name('admin/payin-report');
//     Route::match(['get', 'post'], '/admin/payin-report-data', [Admin::class, 'payinReportData'])->name('/admin/payin-report-data');
    

//     Route::match(['get', 'post'], '/admin/bulk-payout', [Admin::class, 'bulkPayout'])->name('/admin/bulk-payout');
//     Route::get('/admin/payout-list', [Admin::class, 'payoutList'])->name('admin.payoutList'); //
//     Route::post('/admin/payout-status/{payoutlist}', [Admin::class, 'payoutStatus'])->name('admin.payoutStatus'); //

//     Route::match(['get', 'post'], '/admin/wallet-report-export', [Admin::class, 'walletReportDataExport'])->name('/admin/wallet-report-export');
//     Route::match(['get', 'post'], '/admin/payout-report-export', [Admin::class, 'payoutReportDataExport'])->name('/admin/payout-report-export');
//     Route::match(['get', 'post'], '/admin/topup-report-export', [Admin::class, 'topupReportDataExport'])->name('/admin/topup-report-export');
//     Route::match(['get', 'post'], '/admin/payin-report-export', [Admin::class, 'payinReportDataExport'])->name('/admin/payin-report-export');

//     Route::match(['get', 'post'], '/admin/read-csv', [Admin::class, 'readCSV'])->name('/admin/read-csv');
//     Route::match(['get', 'post'], '/admin/read-csv-save', [Admin::class, 'datasavepre'])->name('/admin/read-csv-save');
//     Route::match(['get', 'post'], '/admin/get-daily-data', [Admin::class, 'getDailyData'])->name('/admin/get-daily-data');
//     Route::match(['get', 'post'], '/admin/get-payout-status', [Admin::class, 'getPayoutData'])->name('/admin/get-payout-status');
    
//     Route::match(['get', 'post'], '/admin/user-dashboard/{id?}', [Admin::class, 'userDashboard'])->name('/admin/user-dashboard/{id?}');

//     Route::get('/user/dashboard', [Users::class, 'index'])->name('user/dashboard');
//     Route::get('/user/dopayin', [Users::class, 'doPayin'])->name('user/dopayin');
//     Route::get('/user/dopayout', [Users::class, 'doPayout'])->name('user/dopayout'); //
//     Route::post('/user/store-dopayout', [Users::class, 'storePayout'])->name('user.storePayout'); //
//     Route::get('/user/bank-list', [Users::class, 'bankList'])->name('user/bankList'); //
//     Route::get('/user/add-bank', [Users::class, 'addBank'])->name('user.addBank'); //
//     Route::post('/user/store-bank', [Users::class, 'storeBank'])->name('user.storeBank'); // 
//     Route::get('/user/edit-bank/{bank}', [Users::class, 'editBank'])->name('user.editBank'); // 
//     Route::get('/user/update-bank/{bank}', [Users::class, 'updateBank'])->name('user.updateBank'); //
//     Route::get('/user/delete-bank/{bank}', [Users::class, 'deleteBank'])->name('user.deleteBank'); //
//     Route::match(['get', 'post'], '/user/change-password', [Users::class, 'changePassword'])->name('user/change-password');
//     Route::get('/user/view-profile', [Users::class, 'viewProfile']);
//     Route::get('/user/wallet-report', [Users::class, 'walletReport'])->name('user/wallet-report');
//     Route::match(['get', 'post'], '/user/wallet-report-data', [Users::class, 'walletReportData'])->name('/user/wallet-report-data');

//     Route::match(['get', 'post'], '/user/wallet-report-export', [Users::class, 'walletReportDataExport'])->name('/user/wallet-report-export');
//     Route::match(['get', 'post'], '/user/payout-report-export', [Users::class, 'payoutReportDataExport'])->name('/user/payout-report-export');
//     Route::match(['get', 'post'], '/user/topup-report-export', [Users::class, 'topupReportDataExport'])->name('/user/topup-report-export');
//     Route::match(['get', 'post'], '/user/payin-report-export', [Users::class, 'payinReportDataExport'])->name('/user/payin-report-export');

//     Route::get('/user/payout-report', [Users::class, 'payoutReport'])->name('user/payout-report');
//     Route::match(['get', 'post'], '/user/payout-report-data', [Users::class, 'payoutReportData'])->name('/user/payout-report-data');
//     Route::get('/user/wallet-topup-report', [Users::class, 'walletTopupReport'])->name('user/wallet-topup-report');
//     Route::match(['get', 'post'], '/user/wallet-topup-report-data', [Users::class, 'walletTopupReportData'])->name('/user/wallet-topup-report-data');
//     Route::match(['get', 'post'], '/user/dev-setting', [Users::class, 'devSetting'])->name('/user/dev-setting');
//     Route::match(['get', 'post'], '/user/api-docs', [Users::class, 'apiDocs'])->name('/user/api-docs');

//     Route::get('/user/payin-report', [Users::class, 'payinReport'])->name('user/payin-report');
//     Route::match(['get', 'post'], '/user/payin-report-data', [Users::class, 'payinReportData'])->name('/user/payin-report-data');

//     Route::get('/staff/dashboard', [Staff::class, 'index'])->name('staff/dashboard');

//     Route::match(['get', 'post'], '/staff/add-user', [Staff::class, 'addUsers'])->name('/staff/add-user');

//     Route::match(['get', 'post'], '/staff/change-password', [Staff::class, 'changePassword'])->name('staff/change-password');
//     Route::get('/staff/view-profile', [Staff::class, 'viewProfile']);
//     Route::get('/staff/wallet-report', [Staff::class, 'walletReport'])->name('staff/wallet-report');
//     Route::get('/staff/payout-report', [Staff::class, 'payoutReport'])->name('staff/payout-report');
//     Route::match(['get', 'post'], '/staff/wallet-topup', [Staff::class, 'WalletTopup'])->name('/staff/wallet-topup');
//     Route::match(['get', 'post'], '/staff/wallet-topup-request', [Staff::class, 'WalletTopupRequest'])->name('/staff/wallet-topup-request');
//     Route::match(['get', 'post'], '/staff/wallet-topup-search', [Staff::class, 'WalletTopupSearch'])->name('/staff/wallet-topup-search');
//     Route::match(['get', 'post'], '/staff/wallet-topup-report', [Staff::class, 'WalletTopupReport'])->name('/staff/wallet-topup-report');

//     Route::match(['get', 'post'], '/staff/topup-report-export', [Staff::class, 'topupReportDataExport'])->name('/admin/topup-report-export');


//     Route::get('/agent/dashboard', [Agent::class, 'index'])->name('agent/dashboard');
//     Route::get('/agent/users', [Agent::class, 'usersData'])->name('agent/users');
//     Route::get('/agent/wallet-report', [Agent::class, 'walletReport'])->name('agent/wallet-report');
//     Route::match(['get', 'post'], '/agent/wallet-report-data', [Agent::class, 'walletReportData'])->name('/agent/wallet-report-data');
//     Route::get('/agent/payout-report', [Agent::class, 'payoutReport'])->name('agent/payout-report');
//     Route::match(['get', 'post'], '/agent/payout-report-data', [Agent::class, 'payoutReportData'])->name('/agent/payout-report-data');
//     Route::get('/agent/wallet-topup-report', [Agent::class, 'walletTopupReport'])->name('agent/wallet-topup-report');
//     Route::match(['get', 'post'], '/agent/topup-report-export', [Agent::class, 'topupReportDataExport'])->name('/agent/topup-report-export');
//     Route::match(['get', 'post'], '/agent/change-password', [Agent::class, 'changePassword'])->name('agent/change-password');


//     Route::match(['get', 'post'], '/agent/add-agent', [Agent::class, 'addAgentUser'])->name('add-agent-user');

//     Route::get('/admin/settlement', [Admin::class, 'manageSettlement'])->name('manage-settlement'); 
//     Route::get('/admin/add-settlement/{user}', [Admin::class, 'addSettlement'])->name('add-settlement');
//     //Route::match(['get', 'post'], '/admin/add-user', [Admin::class, 'addUsers'])->name('add-user');
//     //Route::put('/admin/update-user/{user}', [Admin::class, 'updateUser'])->name('update-user');
//     Route::put('/admin/update-settlement/{user}', [Admin::class, 'updateSettlement'])->name('update-settlement');

// });

// Admin route
Route::middleware(['auth', 'user-access:0'])->group(function () {
    Route::get('/admin/dashboard', [Admin::class, 'index']);
    Route::match(['get', 'post'], '/admin/change-password', [Admin::class, 'changePassword'])->name('change-password');
    Route::get('/admin/view-profile', [Admin::class, 'viewProfile']);
    Route::get('/admin/manage-users', [Admin::class, 'manageUsers'])->name('manage-users');
    Route::match(['get', 'post'], '/admin/add-user', [Admin::class, 'addUsers'])->name('add-user');
    Route::get('/admin/edit-user/{user}', [Admin::class, 'editUser'])->name('edit-user');
    Route::put('/admin/update-user/{user}', [Admin::class, 'updateUser'])->name('update-user');

    Route::get('/admin/manage-staff', [Admin::class, 'manageStaff'])->name('manage-staff');
    Route::match(['get', 'post'], '/admin/add-staff', [Admin::class, 'addStaff'])->name('add-staff');
    Route::get('/admin/edit-staff/{user}', [Admin::class, 'editStaff'])->name('edit-staff');
    Route::put('/admin/update-staff/{user}', [Admin::class, 'updateStaff'])->name('update-staff');
    
    Route::get('/admin/manage-agent', [Admin::class, 'manageAgent'])->name('manage-agent');
    Route::match(['get', 'post'], '/admin/add-agent', [Admin::class, 'addAgent'])->name('add-agent');
    
    Route::post('/admin/update-users-status', [Admin::class, 'manageUsersStatus'])->name('manage-users-status');
    Route::post('/admin/update-users-pay-status', [Admin::class, 'manageUsersPayStatus'])->name('manage-users-pay-status');
    Route::post('/admin/reset-users-password', [Admin::class, 'resetUserPassword'])->name('reset-users-password');
    Route::match(['get', 'post'], '/admin/manage-user-charge/{id?}', [Admin::class, 'manageUserCharge'])->name('/admin/manage-user-charge/{id?}');
    
    Route::post('/admin/save-user-charge', [Admin::class, 'saveUserCharge'])->name('/admin/save-user-charge');
    Route::any('/admin/update-user-charge/{id}', [Admin::class, 'updateUserCharge'])->name('/admin/update-user-charge');   // new added 10/5/2024
    Route::get('/admin/delete-admin-user-charge/{id}', [Admin::class, 'deleteUserCharge'])->name('/admin/delete-user-charge');  // new added 10/5/2024
    
    Route::post('/admin/save-user-ip', [Admin::class, 'saveUserIP'])->name('/admin/save-user-ip');
    Route::any('/admin/update-user-ip/{id}', [Admin::class, 'updateUserIP'])->name('/admin/update-user-ip');   // new added 10/5/2024
    Route::get('/admin/delete-user-ip/{id}', [Admin::class, 'deleteUserIP'])->name('/admin/delete-user-ip');   // new added 10/5/2024
    
    Route::post('/admin/save-user-platform-charge', [Admin::class, 'saveUserPlatformCharge'])->name('/admin/save-user-platform-charge');
    Route::any('/admin/update-user-platform-charge/{id}', [Admin::class, 'updateUserPlatformCharge'])->name('/admin/update-user-platform-charge');   // new added 10/5/2024
    Route::get('/admin/delete-user-platform-charge/{id}', [Admin::class, 'deleteUserPlatformCharge'])->name('/admin/delete-user-platform-charge');   // new added 10/5/2024
    
    Route::post('/admin/save-user-setting', [Admin::class, 'saveUserSetting'])->name('/admin/save-user-setting');
    Route::post('/admin/update-user-fund', [Admin::class, 'updateUserFund'])->name('/admin/update-user-fund');
    Route::get('/admin/wallet-report', [Admin::class, 'walletReport'])->name('admin/wallet-report');
    Route::match(['get', 'post'], '/admin/wallet-report-data', [Admin::class, 'walletReportData'])->name('/admin/wallet-report-data');
    Route::get('/admin/payout-report', [Admin::class, 'payoutReport'])->name('admin/payout-report');
    Route::match(['get', 'post'], '/admin/payout-report-data', [Admin::class, 'payoutReportData'])->name('/admin/payout-report-data');
    Route::match(['get', 'post'], '/admin/wallet-topup', [Admin::class, 'WalletTopup'])->name('/admin/wallet-topup');
    Route::match(['get', 'post'], '/admin/wallet-topup-request', [Admin::class, 'WalletTopupRequest'])->name('/admin/wallet-topup-request');
    Route::match(['get', 'post'], '/admin/wallet-topup-search', [Admin::class, 'WalletTopupSearch'])->name('/admin/wallet-topup-search');
    Route::match(['get', 'post'], '/admin/wallet-topup-report', [Admin::class, 'WalletTopupReport'])->name('/admin/wallet-topup-report');
    Route::match(['get', 'post'], '/admin/manage-staff-ip/{id?}', [Admin::class, 'manageStaffIp'])->name('/admin/manage-staff-ip/{id?}');
    Route::match(['get', 'post'], '/admin/save-satff-ip', [Admin::class, 'addStaffIp'])->name('/admin/save-satff-ip');
    Route::match(['get', 'post'], '/admin/deduct-chargeback', [Admin::class, 'deductChargeback'])->name('/admin/deduct-chargeback');

    Route::get('/admin/deduct-chargeback', [Admin::class, 'deductChargeback'])->name('admin/deduct-chargeback');
    Route::get('/admin/chargeback-report', [Admin::class, 'chargebackReport'])->name('admin/chargeback-report');
    Route::match(['get', 'post'], '/admin/chargeback-report-data', [Admin::class, 'chargebackReportData'])->name('/admin/chargeback-report-data');

    Route::get('/admin/payin-report', [Admin::class, 'payinReport'])->name('admin/payin-report');
    Route::match(['get', 'post'], '/admin/payin-report-data', [Admin::class, 'payinReportData'])->name('/admin/payin-report-data');
    

    Route::match(['get', 'post'], '/admin/bulk-payout', [Admin::class, 'bulkPayout'])->name('/admin/bulk-payout');
    Route::get('/admin/payout-list', [Admin::class, 'payoutList'])->name('admin.payoutList'); //
    Route::post('/admin/payout-status/{payoutlist}', [Admin::class, 'payoutStatus'])->name('admin.payoutStatus'); //

    Route::match(['get', 'post'], '/admin/wallet-report-export', [Admin::class, 'walletReportDataExport'])->name('/admin/wallet-report-export');
    Route::match(['get', 'post'], '/admin/payout-report-export', [Admin::class, 'payoutReportDataExport'])->name('/admin/payout-report-export');
    Route::match(['get', 'post'], '/admin/topup-report-export', [Admin::class, 'topupReportDataExport'])->name('/admin/topup-report-export');
    Route::match(['get', 'post'], '/admin/payin-report-export', [Admin::class, 'payinReportDataExport'])->name('/admin/payin-report-export');

    Route::match(['get', 'post'], '/admin/read-csv', [Admin::class, 'readCSV'])->name('/admin/read-csv');
    Route::match(['get', 'post'], '/admin/read-csv-save', [Admin::class, 'datasavepre'])->name('/admin/read-csv-save');
    Route::match(['get', 'post'], '/admin/get-daily-data', [Admin::class, 'getDailyData'])->name('/admin/get-daily-data');
    Route::match(['get', 'post'], '/admin/get-payout-status', [Admin::class, 'getPayoutData'])->name('/admin/get-payout-status');
    
    Route::match(['get', 'post'], '/admin/user-dashboard/{id?}', [Admin::class, 'userDashboard'])->name('/admin/user-dashboard/{id?}');


    Route::get('/admin/settlement', [Admin::class, 'manageSettlement'])->name('manage-settlement'); 
    Route::get('/admin/add-settlement/{user}', [Admin::class, 'addSettlement'])->name('add-settlement');
    //Route::match(['get', 'post'], '/admin/add-user', [Admin::class, 'addUsers'])->name('add-user');
    //Route::put('/admin/update-user/{user}', [Admin::class, 'updateUser'])->name('update-user');
    Route::put('/admin/update-settlement/{user}', [Admin::class, 'updateSettlement'])->name('update-settlement');
    Route::get('/admin/view-settlement/{user}', [Admin::class, 'viewSettlement'])->name('view-settlement');

    // Route::get('/admin/delete-user-ip', [Admin::class, 'deleteUserIp'])->name('admin/delete-user-ip');
    // Route::post('/admin/delete-user-charge', [Admin::class, 'deleteUserCharge'])->name('admin/delete-user-charge');

    Route::get('/admin/payrequest-list', [Admin::class, 'paymentRequestList'])->name('admin.payrequest-list'); //Fund Request Module -> Admin appproval 4th Feb 24 --- start
    Route::post('/admin/approve-reject', [Admin::class, 'payReqApprRej'])->name('admin.approve-reject'); //Fund Request Module -> Admin appproval 4th Feb 24 --- end
});

// Agent route
Route::middleware(['auth', 'user-access:3'])->group(function () {
    Route::get('/agent/dashboard', [Agent::class, 'index'])->name('agent/dashboard');
    Route::get('/agent/users', [Agent::class, 'usersData'])->name('agent/users');
    Route::get('/agent/wallet-report', [Agent::class, 'walletReport'])->name('agent/wallet-report');
    Route::match(['get', 'post'], '/agent/wallet-report-data', [Agent::class, 'walletReportData'])->name('/agent/wallet-report-data');
    Route::get('/agent/payin-report', [Agent::class, 'payinReport'])->name('agent/payin-report');
    Route::match(['get', 'post'], '/agent/payin-report-data', [Agent::class, 'payinReportData'])->name('/agent/payin-report-data');
    Route::get('/agent/payout-report', [Agent::class, 'payoutReport'])->name('agent/payout-report');
    Route::match(['get', 'post'], '/agent/payout-report-data', [Agent::class, 'payoutReportData'])->name('/agent/payout-report-data');
    Route::get('/agent/wallet-topup-report', [Agent::class, 'walletTopupReport'])->name('agent/wallet-topup-report');
    Route::match(['get', 'post'], '/agent/topup-report-export', [Agent::class, 'topupReportDataExport'])->name('/agent/topup-report-export');
    Route::match(['get', 'post'], '/agent/change-password', [Agent::class, 'changePassword'])->name('agent/change-password');
    Route::match(['get', 'post'], '/agent/add-agent', [Agent::class, 'addAgentUser'])->name('add-agent-user');


    // new route - 28-04-2024
    
    Route::get('/agent/banks', [Agent::class, 'bankList'])->name('agent.bankList');
    Route::get('/agent/add-bank', [Agent::class, 'addBank'])->name('agent.addBank');
    Route::post('/agent/stote-bank', [Agent::class, 'storeBank'])->name('agent.storeBank');
    Route::get('/agent/edit-bank/{bank}', [Agent::class, 'editBank'])->name('agent.editBank'); 
    Route::get('/agent/update-bank/{bank}', [Agent::class, 'updateBank'])->name('agent.updateBank'); 
    Route::get('/agent/delete-bank/{bank}', [Agent::class, 'deleteBank'])->name('agent.deleteBank'); 

    Route::get('/agent/dopayout', [Agent::class, 'doPayout'])->name('agent.dopayout'); 
    Route::post('/agent/store-dopayout', [Agent::class, 'storePayout'])->name('agent.storePayout');

    Route::get('/agent/comission', [Agent::class, 'comission'])->name('agent.comission'); 
    Route::post('/agent/save-user-setting', [Agent::class, 'saveUserSetting'])->name('agent.save-user-setting');
    Route::post('/agent/save-user-charge', [Agent::class, 'saveUserCharge'])->name('agent.save-user-charge');
    Route::match(['get', 'post'], '/agent/manage-user-charge/{id?}', [Agent::class, 'manageUserCharge'])->name('/agent/manage-user-charge/{id?}');
    Route::match(['get', 'post'], '/agent/user-dashboard/{id?}', [Agent::class, 'userDashboard'])->name('/agent/user-dashboard/{id?}');
    Route::post('/agent/save-user-platform-charge', [Agent::class, 'saveUserPlatformCharge'])->name('/agent/save-user-platform-charge');
    Route::post('/agent/save-user-ip', [Agent::class, 'saveUserIP'])->name('/agent/save-user-ip');

    // Route::get('/agent/users', [Agent::class, 'usersData'])->name('/agent/users');
    // Route::get('/agent/add-user', [Agent::class, 'addAgentUser'])->name('/agent/add-user');
    Route::match(['get', 'post'], '/agent/add-user', [Agent::class, 'addAgentUser'])->name('/agent/add-user');
    
    Route::match(['get', 'post'], '/agent/payout-report-export', [Agent::class, 'payoutReportDataExport'])->name('/agent/payout-report-export');
    Route::match(['get', 'post'], '/agent/payin-report-export', [Agent::class, 'payinReportDataExport'])->name('/agent/payin-report-export');
    


});

// Payin-Payout route user
Route::middleware(['auth', 'user-access:1'])->group(function () {
    Route::get('/user/dashboard', [Users::class, 'index'])->name('user/dashboard');
    Route::get('/user/dopayin', [Users::class, 'doPayin'])->name('user/dopayin');
    Route::get('/user/dopayout', [Users::class, 'doPayout'])->name('user/dopayout'); //
    Route::post('/user/store-dopayout', [Users::class, 'storePayout'])->name('user.storePayout'); //
    Route::get('/user/bank-list', [Users::class, 'bankList'])->name('user/bankList'); //
    Route::get('/user/add-bank', [Users::class, 'addBank'])->name('user.addBank'); //
    Route::post('/user/store-bank', [Users::class, 'storeBank'])->name('user.storeBank'); // 
    Route::get('/user/edit-bank/{bank}', [Users::class, 'editBank'])->name('user.editBank'); // 
    Route::get('/user/update-bank/{bank}', [Users::class, 'updateBank'])->name('user.updateBank'); //
    Route::get('/user/delete-bank/{bank}', [Users::class, 'deleteBank'])->name('user.deleteBank'); //
    Route::match(['get', 'post'], '/user/change-password', [Users::class, 'changePassword'])->name('user/change-password');
    Route::get('/user/view-profile', [Users::class, 'viewProfile']);
    Route::get('/user/wallet-report', [Users::class, 'walletReport'])->name('user/wallet-report');
    Route::match(['get', 'post'], '/user/wallet-report-data', [Users::class, 'walletReportData'])->name('/user/wallet-report-data');

    Route::match(['get', 'post'], '/user/wallet-report-export', [Users::class, 'walletReportDataExport'])->name('/user/wallet-report-export');
    Route::match(['get', 'post'], '/user/payout-report-export', [Users::class, 'payoutReportDataExport'])->name('/user/payout-report-export');
    Route::match(['get', 'post'], '/user/topup-report-export', [Users::class, 'topupReportDataExport'])->name('/user/topup-report-export');
    Route::match(['get', 'post'], '/user/payin-report-export', [Users::class, 'payinReportDataExport'])->name('/user/payin-report-export');

    Route::get('/user/payout-report', [Users::class, 'payoutReport'])->name('user/payout-report');
    Route::match(['get', 'post'], '/user/payout-report-data', [Users::class, 'payoutReportData'])->name('/user/payout-report-data');
    Route::get('/user/wallet-topup-report', [Users::class, 'walletTopupReport'])->name('user/wallet-topup-report');
    Route::match(['get', 'post'], '/user/wallet-topup-report-data', [Users::class, 'walletTopupReportData'])->name('/user/wallet-topup-report-data');
    Route::match(['get', 'post'], '/user/dev-setting', [Users::class, 'devSetting'])->name('/user/dev-setting');
    Route::match(['get', 'post'], '/user/api-docs', [Users::class, 'apiDocs'])->name('/user/api-docs');

    Route::get('/user/payin-report', [Users::class, 'payinReport'])->name('user/payin-report');
    Route::match(['get', 'post'], '/user/payin-report-data', [Users::class, 'payinReportData'])->name('/user/payin-report-data');


    Route::get('/user/payrequest-list', [Users::class, 'paymentRequestList'])->name('user/payrequest-list'); //Fund Request Module 2nd Feb 24 --- start
    Route::get('/user/add-payrequest', [Users::class, 'addPayRequest'])->name('user.add-payrequest'); //
    Route::post('/user/store-payrequest', [Users::class, 'storePayRequest'])->name('user.payrequest'); // 
    Route::get('/user/edit-bank1/{bank}', [Users::class, 'editBank1'])->name('user.editBank1'); // 
    Route::get('/user/update-bank1/{bank}', [Users::class, 'updateBank1'])->name('user.updateBank1'); //
    Route::get('/user/delete-bank1/{bank}', [Users::class, 'deleteBank1'])->name('user.deleteBank1'); //Fund Request Module 2nd Feb 24 -- end
});

// Payout User
Route::middleware(['auth', 'user-access:4'])->group(function () {
    Route::get('/payout-user/dashboard', [PayoutUsers::class, 'index'])->name('payout-user/dashboard');
    // Route::get('/user/dopayin', [Users::class, 'doPayin'])->name('user/dopayin');
    Route::get('/payout-user/dopayout', [PayoutUsers::class, 'doPayout'])->name('payout-user/dopayout'); //
    Route::post('/payout-user/store-dopayout', [PayoutUsers::class, 'storePayout'])->name('payout-user.storePayout'); //
    Route::get('/payout-user/bank-list', [PayoutUsers::class, 'bankList'])->name('payout-user/bankList'); //
    Route::get('/payout-user/add-bank', [PayoutUsers::class, 'addBank'])->name('payout-user.addBank'); //
    Route::post('/payout-user/store-bank', [PayoutUsers::class, 'storeBank'])->name('payout-user.storeBank'); // 
    Route::get('/payout-user/edit-bank/{bank}', [PayoutUsers::class, 'editBank'])->name('payout-user.editBank'); // 
    Route::get('/payout-user/update-bank/{bank}', [PayoutUsers::class, 'updateBank'])->name('payout-user.updateBank'); //
    Route::get('/payout-user/delete-bank/{bank}', [PayoutUsers::class, 'deleteBank'])->name('payout-user.deleteBank'); //
    Route::match(['get', 'post'], '/payout-user/change-password', [PayoutUsers::class, 'changePassword'])->name('payout-user/change-password');
    Route::get('/payout-user/view-profile', [PayoutUsers::class, 'viewProfile']);
    // Route::get('/user/wallet-report', [Users::class, 'walletReport'])->name('user/wallet-report');
    // Route::match(['get', 'post'], '/user/wallet-report-data', [Users::class, 'walletReportData'])->name('/user/wallet-report-data');

    // Route::match(['get', 'post'], '/user/wallet-report-export', [Users::class, 'walletReportDataExport'])->name('/user/wallet-report-export');
    Route::match(['get', 'post'], '/payout-user/payout-report-export', [PayoutUsers::class, 'payoutReportDataExport'])->name('/payout-user/payout-report-export');
    Route::match(['get', 'post'], '/payout-user/topup-report-export', [PayoutUsers::class, 'topupReportDataExport'])->name('/payout-user/topup-report-export');
    // Route::match(['get', 'post'], '/user/payin-report-export', [Users::class, 'payinReportDataExport'])->name('/user/payin-report-export');

    Route::get('/payout-user/payout-report', [PayoutUsers::class, 'payoutReport'])->name('payout-user/payout-report');
    Route::match(['get', 'post'], '/payout-user/payout-report-data', [PayoutUsers::class, 'payoutReportData'])->name('/payout-user/payout-report-data');
    Route::get('/payout-user/wallet-topup-report', [PayoutUsers::class, 'walletTopupReport'])->name('payout-user/wallet-topup-report');
    Route::match(['get', 'post'], '/payout-user/wallet-topup-report-data', [PayoutUsers::class, 'walletTopupReportData'])->name('/payout-user/wallet-topup-report-data');
    Route::match(['get', 'post'], '/payout-user/dev-setting', [PayoutUsers::class, 'devSetting'])->name('/payout-user/dev-setting');
    Route::match(['get', 'post'], '/payout-user/api-docs', [PayoutUsers::class, 'apiDocs'])->name('/payout-user/api-docs');

    // Route::get('/user/payin-report', [Users::class, 'payinReport'])->name('user/payin-report');
    // Route::match(['get', 'post'], '/user/payin-report-data', [Users::class, 'payinReportData'])->name('/user/payin-report-data');

    Route::get('/payout-user/payrequest-list', [PayoutUsers::class, 'paymentRequestList'])->name('payout-user/payrequest-list'); //Fund Request Module 2nd Feb 24 --- start
    Route::get('/payout-user/add-payrequest', [PayoutUsers::class, 'addPayRequest'])->name('payout-user.add-payrequest'); //
    Route::post('/payout-user/store-payrequest', [PayoutUsers::class, 'storePayRequest'])->name('payout-user.payrequest'); // 
    Route::get('/payout-user/edit-bank1/{bank}', [PayoutUsers::class, 'editBank1'])->name('payout-user.editBank1'); // 
    Route::get('/payout-user/update-bank1/{bank}', [PayoutUsers::class, 'updateBank1'])->name('payout-user.updateBank1'); //
    Route::get('/payout-user/delete-bank1/{bank}', [PayoutUsers::class, 'deleteBank1'])->name('payout-user.deleteBank1'); //Fund Request Module 2nd Feb 24 -- end
});

// Staff route
Route::middleware(['auth', 'user-access:2'])->group(function () {
    Route::get('/staff/dashboard', [Staff::class, 'index'])->name('staff/dashboard');

    Route::match(['get', 'post'], '/staff/add-user', [Staff::class, 'addUsers'])->name('/staff/add-user');

    Route::match(['get', 'post'], '/staff/change-password', [Staff::class, 'changePassword'])->name('staff/change-password');
    Route::get('/staff/view-profile', [Staff::class, 'viewProfile']);
    Route::get('/staff/wallet-report', [Staff::class, 'walletReport'])->name('staff/wallet-report');
    Route::get('/staff/payout-report', [Staff::class, 'payoutReport'])->name('staff/payout-report');
    Route::match(['get', 'post'], '/staff/wallet-topup', [Staff::class, 'WalletTopup'])->name('/staff/wallet-topup');
    Route::match(['get', 'post'], '/staff/wallet-topup-request', [Staff::class, 'WalletTopupRequest'])->name('/staff/wallet-topup-request');
    Route::match(['get', 'post'], '/staff/wallet-topup-search', [Staff::class, 'WalletTopupSearch'])->name('/staff/wallet-topup-search');
    Route::match(['get', 'post'], '/staff/wallet-topup-report', [Staff::class, 'WalletTopupReport'])->name('/staff/wallet-topup-report');

    Route::match(['get', 'post'], '/staff/topup-report-export', [Staff::class, 'topupReportDataExport'])->name('/admin/topup-report-export');
});


    // Route::get('/user/payrequest-list', [Users::class, 'paymentRequestList'])->name('user/payrequest-list'); //Fund Request Module 2nd Feb 24 --- start
    // Route::get('/user/add-payrequest', [Users::class, 'addPayRequest'])->name('user.add-payrequest'); //
    // Route::post('/user/store-payrequest', [Users::class, 'storePayRequest'])->name('user.payrequest'); // 
    // Route::get('/user/edit-bank1/{bank}', [Users::class, 'editBank1'])->name('user.editBank1'); // 
    // Route::get('/user/update-bank1/{bank}', [Users::class, 'updateBank1'])->name('user.updateBank1'); //
    // Route::get('/user/delete-bank1/{bank}', [Users::class, 'deleteBank1'])->name('user.deleteBank1'); //Fund Request Module 2nd Feb 24 -- end

    // Route::get('/admin/payrequest-list', [Admin::class, 'paymentRequestList'])->name('admin.payrequest-list'); //Fund Request Module -> Admin appproval 4th Feb 24 --- start
    // Route::post('/admin/approve-reject', [Admin::class, 'payReqApprRej'])->name('admin.approve-reject'); //Fund Request Module -> Admin appproval 4th Feb 24 --- end
