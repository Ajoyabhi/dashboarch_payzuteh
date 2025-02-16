<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayoutIserveU;
use App\Http\Controllers\PayoutIbrpay;
use App\Http\Controllers\CallbackIserveU;
use App\Http\Controllers\CallbackVouch;
use App\Http\Controllers\CheckStatusIserveU;
use App\Http\Controllers\CheckStatusVouch;
use App\Http\Controllers\PayinIserveU;
use App\Http\Controllers\PayinIbr;
use App\Http\Controllers\PayinJipPay;
use App\Http\Controllers\PayoutJipPay;
use App\Http\Controllers\PayinHaodaPay;
use App\Http\Controllers\PayoutHaodaPay;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('v1/doPayout', [PayoutIserveU::class, 'doPayout']);
// Route::post('v2/callback/IserveU', [CallbackIserveU::class, 'callBackData']);
// Route::post('v1/checkstatus', [CheckStatusIserveU::class, 'checkStatus']);
// Route::post('v1/checkuserbalance', [CheckStatusIserveU::class, 'getUserBalance']);


// Route::post('v3/callback/Vouch', [CallbackVouch::class, 'callBackData']);
// Route::post('v3/callback/register', [CallbackVouch::class, 'registerCallBackUrl']);
// Route::post('v3/callback/Vouch-test', [CallbackVouch::class, 'testCallback']);

// Route::post('v2/checkstatus', [CheckStatusVouch::class, 'checkStatus']);

// Route::post('v1/generateUpi', [PayinIserveU::class, 'generatePaymentLink']);
// Route::post('callback/Payin', [PayinIserveU::class, 'callBackData']);
// Route::post('v1/checkPayinStatus', [PayinIserveU::class, 'checkStatus']);

// Route::post('v2/doPayout', [PayoutIbrpay::class, 'doPayout']); //
// Route::post('v2/generateUpi', [PayinIbr::class, 'generatePaymentLink']); ////work
//Route::get('callback/Payin/ibrpay', [PayinIbr::class, 'callBackData']);

// Route::post('callback/Payout/ibrpay', [PayoutIbrpay::class, 'callBackData']);

// Route::match(['get', 'post'], 'callback/Payin/ibrpay', [PayinIbr::class, 'callBackData'])->name('callback/Payin/ibrpay');

//backup 12 May 2024
 //JIP-PAY-IN
// Route::post('v5/generateUpi', [PayinJipPay::class, 'generatePaymentLink']);
// Route::post('v5/payinCallBackData', [PayinJipPay::class, 'callBackData']);
// Route::post('v5/payinCheckStatusPay', [PayinJipPay::class, 'payinCheckStatus']);//payin checkStatus
// Route::post('v5/checkClientCallbackPayin', [PayinJipPay::class, 'checkClientCallbackPayin']);
//JIP-PAY-OUT
// Route::post('v5/doPayout', [PayoutJipPay::class, 'doPayout']);
// Route::post('v5/payoutCallBackData', [PayoutJipPay::class, 'checkCallbackData']);
// Route::post('v5/checkstatusPay', [PayoutJipPay::class, 'doPayoutCheckStatus']);//payout checkStatus

//Route::post('v5/checkClientCallbackPayout', [PayoutJipPay::class, 'checkClientCallbackPayout']);

//Haoda Payin API 21 Mar 24 V6
// Route::post('v6/generateUpi', [PayinHaodaPay::class, 'generatePaymentLink']);
// Route::post('v6/payinCallBackData', [PayinHaodaPay::class, 'callBackData']);
// Route::post('v6/payinCheckStatusPay', [PayinHaodaPay::class, 'payinCheckStatus']);
// Route::post('v6/checkClientCallbackPayin', [PayinHaodaPay::class, 'checkClientCallbackPayin']);


//https://api.paydexsolutions.in/api/callback/Payout/ibrpay
// Route::post('v6/doPayout', [PayoutHaodaPay::class, 'doPayout']);
// Route::post('v6/checkstatusPay', [PayoutHaodaPay::class, 'doPayoutCheckStatus']);
// Route::post('v6/payoutCallBackData', [PayoutHaodaPay::class, 'checkCallbackData']);
// Route::post('v6/checkClientCallbackPayout', [PayoutHaodaPay::class, 'checkClientCallbackPayout']);

//Haoda Payin API 21 Mar 24 V6 (updated on 12th May 2024)
/* Route::post('v5/generateUpi', [PayinHaodaPay::class, 'generatePaymentLink']);
Route::post('v5/payinCallBackData', [PayinHaodaPay::class, 'callBackData']);
Route::post('v5/payinCheckStatusPay', [PayinHaodaPay::class, 'payinCheckStatus']);
Route::post('v5/checkClientCallbackPayin', [PayinHaodaPay::class, 'checkClientCallbackPayin']);


//https://api.paydexsolutions.in/api/callback/Payout/ibrpay
Route::post('v5/doPayout', [PayoutHaodaPay::class, 'doPayout']);
Route::post('v5/checkstatusPay', [PayoutHaodaPay::class, 'doPayoutCheckStatus']);
Route::post('v5/payoutCallBackData', [PayoutHaodaPay::class, 'checkCallbackData']);
Route::post('v5/checkClientCallbackPayout', [PayoutHaodaPay::class, 'checkClientCallbackPayout']);

//JIP-PAY-IN
Route::post('v6/generateUpi', [PayinJipPay::class, 'generatePaymentLink']);
Route::post('v6/payinCallBackData', [PayinJipPay::class, 'callBackData']);
Route::post('v6/payinCheckStatusPay', [PayinJipPay::class, 'payinCheckStatus']);//payin checkStatus
Route::post('v6/checkClientCallbackPayin', [PayinJipPay::class, 'checkClientCallbackPayin']);
//JIP-PAY-OUT
Route::post('v6/doPayout', [PayoutJipPay::class, 'doPayout']);
Route::post('v6/payoutCallBackData', [PayoutJipPay::class, 'checkCallbackData']);
Route::post('v6/checkstatusPay', [PayoutJipPay::class, 'doPayoutCheckStatus']);//payout checkStatus */

//Route::post('v5/checkClientCallbackPayout', [PayoutJipPay::class, 'checkClientCallbackPayout']);

