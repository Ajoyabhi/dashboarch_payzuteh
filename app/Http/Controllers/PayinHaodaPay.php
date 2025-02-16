<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\UserCharge;
use App\Models\UserIp;
use App\Models\UserTransaction;
use App\Models\PayinModel;
use App\Models\ApiLog;

class PayinHaodaPay extends Controller 
{
    public function generatePaymentLink(Request $request)
    {
        $jsonData   = $request->all(); 
        //echo '<pre>';print_r($jsonData);die;
        $Authorization = $request->header('Authorization');
        
        $validator = Validator::make($request->all(), [
            'bill_amt' => 'required',
            'fullname' => 'required',
            'bill_email' => 'required',
            'bill_phone' => 'required',
            'reference' => 'required|unique:payin_transactions,contactId',
        ]);
    
        if($validator->fails()) {
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => $validator->errors(),
            ];
            return response()->json($responseData, 422);
        }
    
        $getUser = User::where("user_token",$Authorization)->where("status",1)->where("user_type",1)->first();
        if(empty($getUser)){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Invalid credentials',
            ];
            return response()->json($responseData , 401);
        }
        $userId = $getUser->id;
        $api_status = $getUser->api_status;
        if($api_status == 0){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'We are taking downtime due to some technical issues. Please wait till further update',
            ];
            return response()->json($responseData , 200);
        }
        
        // $payin_status = $getUser->payin_status;
        // if($payin_status == 0){
        //     $responseData = [                
        //         'status' => FALSE,
        //         'error'=> TRUE,
        //         'message' => 'You are not authorised person!',
        //     ];
        //     return response()->json($responseData , 200);
        // }
    
        $ipAddress = $request->ip();
        $checkIp = UserIp::where(['userId'=>$userId,'ipAddress'=>$ipAddress])->first();
        if(empty($checkIp)){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'your ip is not whitlisted, requested ip is '.$ipAddress,
            ];
            return response()->json($responseData , 401);
        }

        $txn_id = "NESTPAY".rand(123121,990999).Carbon::now()->timestamp;

        $bill_amt = $jsonData['bill_amt'];
        $fullname = $jsonData['fullname'];
        $bill_email = $jsonData['bill_email'];
        $bill_phone = $jsonData['bill_phone'];
        $reference = $jsonData['reference'];

        //START
        $AuthKeyNest = 'c39175eb696c72825b4e320295c8a739a894534b';
        
        $request = [            
            "reference" => $reference,
            "bill_amt" => $bill_amt,
            "fullname" => $fullname,
            "bill_email" => $bill_email,
            "bill_phone" => $bill_phone
        ];
        $payload = json_encode($request);
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.nestpay.in/api/v6/generateUpi',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$payload,
          CURLOPT_HTTPHEADER => array(
            "Authorization: $AuthKeyNest",
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        
        if (isset($error_msg)) {
            echo $error_msg;
        }
        //END

        ApiLog::create([
            'txnId' => $txn_id,
            'request' => $payload,
            'response' => $response,  
            'service' => "GENERATEUPI",
            'service_api' =>"JIPAY",
        ]);
        $responseData = json_decode($response,TRUE);
       // echo '<pre>';print_r($responseData);die;
        //{"status_code":"503","status":"server_busy","message":"Server busy, please try again after some time.","code":"request_failed","type":"server_error","load":1}
        if(!empty($responseData)){
            if(isset($responseData['status_code']) && $responseData['status_code'] == '503'){
                $responseData = [
                    "status" => false,
                    "error" => true,
                    "responseCode" => 503,
                    "message" => "server_busy",
                    "data" => array(
                        "status" => "Server busy, please try again after some time.",
                    ),
                ];
                return response()->json($responseData , 200);
            }
            if(!empty($responseData) && $responseData['status'] == false && $responseData['error'] == true) {
                return response()->json($responseData , 200);
            } else if($responseData['responseCode'] == "200" && $responseData['status'] == true) {
                
                $payment_link = $responseData['data']['payment_link'];
                $payLink = $responseData['data']['PaymentProcessUrl'];
                $walletTransactionId = $responseData['data']['trasnactionId'];


                PayinModel::create([
                    'userId' => $userId,
                    'txnId' => $txn_id,
                    'orderId' => $reference,
                    'contactId' => $reference,
                    'amount' => $bill_amt,
                    'payerName' => $fullname,
                    'payerMobile' => $bill_phone,
                    'status' => "PENDING",
                    'api' => "NESTPAY",
                    'IpAddress' => $ipAddress,
                ]);
                
                $responseData = [
                    "status" => true,
                    "error" => false,
                    "responseCode" => 200,
                    "message" => "SUCCESS",
                    "data" => array(
                        "payment_link" => $payment_link,
                        "PaymentProcessUrl" => $payLink,
                        "ReferenceId" => $reference,
                        "trasnactionId" => $txn_id,
                        "status" => "SUCCESS",
                    ),
                ];
                return response()->json($responseData , 200);
            }
        }

        $responseData = [
            "status" => FALSE,
            "error" => TRUE,
            "responseCode" => 200,
            "message" => "FAILED",
            "data" => array(
                "ReferenceId" => $reference,
                "trasnactionId" => $txn_id,
                "status" => "FAILED",
                "message" => 'Something Error!',
            ),
        ];
        return response()->json($responseData , 200);
    }

    function callBackData(Request $request)
    {
        $callbackResponse = file_get_contents('php://input');
        $resArr = json_decode($callbackResponse, true);
        ApiLog::create([
            'txnId' => "",
            'request' => "",
            'response' => $callbackResponse,  
            'service' => "CALLBACK-PAYIN",
            'service_api' =>"JIPAY",   
        ]);
        
        if(!empty($resArr) && $resArr['status'] == "success") {
            $PayerAmount = $resArr['data']['amount'];
            $BankRRN = isset($resArr['data']['UTR']) ? $resArr['data']['UTR'] : '';
            $transID = $resArr['data']['order_id'];//$resArr['transID'];
            $checkTxn = PayinModel::where("orderId",$resArr['data']['order_id'])->get()->toArray();

            if(count($checkTxn) == 0){
                ApiLog::create([
                    'txnId' => "Transation not found into our DB, API orderID: ".$resArr['data']['order_id'],
                    'request' => "",
                    'response' => $callbackResponse,  
                    'service' => "CALLBACK-PAYIN",
                    'service_api' =>"JIPAY",   
                ]);
            }

            $userId = $checkTxn[0]['userId'];
            $txnId = $checkTxn[0]['txnId'];
            $referenceNumber = $resArr['data']['order_id'];
            $ipAddress = $checkTxn[0]['IpAddress'];

            $getUser = User::where("id",$userId)->first();
            $getCommission = UserCharge::where('start_amount', '<=', $PayerAmount)->where('end_amount', '>=', $PayerAmount)->where('userId', '=', $userId)->first();
            if(empty($getCommission)){
                $charge = 2;
                $chargeType = "P";
            }else{
                // $charge = $getCommission->payin_charge;
                $charge = $getCommission->payin_total_charge;
                $chargeType = $getCommission->payin_charge_type;
            }
            $gst = 18;
            if($chargeType == "F"){
                $totalCharge = $charge;
                $totalGst = ($totalCharge*$gst)/100;
            }else if($chargeType == "P"){
                $totalCharge = ($PayerAmount*$charge)/100;
                $totalGst = ($totalCharge*$gst)/100;
            }

            $totalAddedAmount = $PayerAmount-$totalCharge-$totalGst;
            $openBal = $getUser->wallet;
            $closeBal = $openBal+$totalAddedAmount;

            $UserInstance = new User();
            $UserInstance->addFund($userId,$totalAddedAmount);

            $remark = "Money Added Via Upi ";
            UserTransaction::create([
                'userId' => $userId,
                'txnId' => $txnId,
                'orderId' => $referenceNumber,
                'type' => "CREDIT",  
                'operator' => "PAYIN",
                'openBalance' =>$openBal, 
                'amount' => $totalAddedAmount,
                'walletBalance' =>$closeBal,
                'credit' =>$totalAddedAmount, 
                'debit' =>0,
                "status" => "SUCCESS",
                'remark' => $remark,   
                'api'=>"NESTPAY",
                'requestIp' => $ipAddress,          
                'created_by' => $userId,
            ]);

            $updatePayin = [
                "charge"=>$totalCharge,
                "gst"=>$totalGst,
                "totalAmount"=> $totalAddedAmount,
                "utr"=>$BankRRN,
                "status"=>"SUCCESS",
            ];                  
            $PayinModel = new PayinModel();      
            $PayinModel->updatePayInData($updatePayin,$txnId);

           // $datasend = $callbackResponse;//passed as it is coming
            /* email mobile added */
            $resArr['mobile'] = $getUser->mobile;
            $resArr['email'] = $getUser->email;
            $datasend = json_encode($resArr);//passed as it is coming
            $callbackUrl = $getUser->payin_callback;
            
            if(!empty($callbackUrl)){
            
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $callbackUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>$datasend,
                    CURLOPT_HTTPHEADER => array(                                
                        'Content-Type: application/json'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);

                ApiLog::create([
                    'txnId' => "callback sent to client: ".$transID,
                    'request' => "callback URL: ". $callbackUrl,
                    'response' => $datasend,  
                    'service' => "CALLBACK-PAYIN-CLIENT-SENT",
                    'service_api' =>"JIPAY",   
                ]);
            
            } else {
                ApiLog::create([
                    'txnId' => "",
                    'request' => "callback URL: ". $callbackUrl,
                    'response' => $datasend,  
                    'service' => "CALLBACK-PAYIN-CLIENT-FAILED",
                    'service_api' =>"JIPAY",   
                    ]);
            }
        }
    }

    public function checkClientCallbackPayin(Request $request) { 
        $callbackResponse = file_get_contents('php://input');
        $response = urldecode($callbackResponse);
        ApiLog::create([
            'txnId' => "",
            'request' => "",
            'response' => $response,  
            'service' => "CALLBACK-PAYIN-CLIENT-RECEIVED",
            'service_api' =>"JIPAY",   
        ]);
    }

    public function payinCheckStatus(Request $request) {
        $Authorization = $request->header('Authorization');
        $getUser = User::where("user_token",$Authorization)->where("status",1)->whereIn('user_type', ['1', '4'])->first();
        if(empty($getUser)){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Invalid credentials!',
            ];
            return response()->json($responseData , 401);
        }
        
        $jsonData = $request->json()->all();

        $referenceNumber = $jsonData['referenceNumber'];//this is ReferenceId taken from the response of Payin API response like PDX....

        $payin_details = PayinModel::where('orderId', $referenceNumber)->first();
        $remark = isset($payin_details->remark) ? $payin_details->remark : '';
        
        if(!empty($payin_details)){
            $responseData = [
                "status" => $payin_details->status,//"SUCCESS",
                "message" => $remark,
                "data" => array(
                    "payout_ref" => $referenceNumber,
                    "bank_ref" => $payin_details->utr
                ),
            ];
            return response()->json($responseData , 200);
        } else {
            $responseData = [
                "status" => "FAILED",
                "message" => "Transaction is not found!",
                "data" => array(
                    "payout_ref" => $referenceNumber,
                    "bank_ref" => ""
                ),
            ];
        return response()->json($responseData , 200);
        }
    }


    //START
    /* 
    $clientId = 'HRNsgFzJTE4203';
        $collectionClientId = 'Mbd9vk35fy240311034913';
        $PayoutClientId = 'Mbd9vk35fy240311034913';
    $request = [ "order_id" => $jsonData['referenceNumber'] ];
    $payload = json_encode($request);
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://jupiter.haodapayments.com/api/v3/collection/status',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$payload,
      CURLOPT_HTTPHEADER => array(
        "x-client-id: $clientId",
        "x-client-secret: $collectionClientId",
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    
    echo $response."~~~";
    die(); */
    //END

}
