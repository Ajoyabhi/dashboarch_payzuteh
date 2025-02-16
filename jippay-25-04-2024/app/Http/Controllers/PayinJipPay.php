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

class PayinJipPay extends Controller 
{
    public function generatePaymentLink(Request $request)
    {
        $jsonData = $request->json()->all();
        $Authorization = $request->header('Authorization');
       
        $validator = Validator::make($request->all(), [
            'referenceNumber' => 'required|unique:payin_transactions,orderId',
            'amount' => 'required',
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

        $txn_id = "JIPAY".rand(123121,990999).Carbon::now()->timestamp;

        $amount = $request['amount'];
        $referenceNumber = $request['referenceNumber'];

        $key = 'KEY1e779794e9deb9376cbd';//fixed
        $token = '59444a2447b809659608b95a1';//fixed
        $request = [            
            "key" => $key,
            "token" => $token,
            "amount" => $amount,
            "agent_id" =>$referenceNumber
        ];

        //$payload = json_encode($request);
        $payload = json_encode($request);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://payin.paydexsolutions.com/prod/payin");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "key=$key&token=$token&agent_id=$referenceNumber&amount=$amount");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        ApiLog::create([
            'txnId' => $txn_id,
            'request' => ($payload),
            'response' => $response,  
            'service' => "GENERATEUPI",
            'service_api' =>"JIPAY-PAYIN",   
        ]);

        $responseData = json_decode($response,TRUE);
        if($responseData['status'] == "SUCCESS")
        {
            $payLink = $responseData['intentData'];
            $walletTransactionId = $responseData['txn_id'];
            
            PayinModel::create([
                'userId' => $userId,
                'txnId' => $txn_id,
                'orderId' => $referenceNumber,
                'contactId' => $walletTransactionId,
                'amount' => $amount,
                'status' => "PENDING",
                'IpAddress' => $ipAddress,
            ]);

            $responseData = [
                "status" => true,
                "error" => false,
                "responseCode" => 200,
                "message" => "SUCCESS",
                "data" => array(
                    "intentLink" => $payLink,
                    "ReferenceId" => $referenceNumber,
                    "trasnactionId" => $txn_id,
                    "status" => "SUCCESS",
                ),
            ];
            return response()->json($responseData , 200);
        }

        $responseData = [
            "status" => FALSE,
            "error" => TRUE,
            "responseCode" => 200,
            "message" => "FAILED",
            "data" => array(
                "ReferenceId" => $referenceNumber,
                "trasnactionId" => $txn_id,
                "status" => "FAILED",
            ),
        ];
        return response()->json($responseData , 200);
    }

    function callBackData(Request $request)
    {
        //$callbackResponse = file_get_contents('php://input');
        $response = json_encode($_POST);
        
        ApiLog::create([
            'txnId' => "",
            'request' => "",
            'response' => $response,  
            'service' => "CALLBACK-PAYIN",
            'service_api' =>"JIPAY",   
        ]);
        $resArr = json_decode($response, TRUE);
        
        if(!empty($resArr) && $resArr['status'] == "SUCCESS") {
            $PayerAmount = $resArr['amount'];
            $BankRRN = isset($resArr['utr']) ? $resArr['utr'] : '';
            $referenceNumber = $resArr['agent_id'];//this is reference Number

            $checkTxn = PayinModel::where("orderId",$referenceNumber)->get()->toArray();

            if(count($checkTxn) == 0){
                ApiLog::create([
                    'txnId' => "Transation not found into our DB, API referenceNumber: ".$referenceNumber,
                    'request' => "",
                    'response' => $response,  
                    'service' => "CALLBACK-PAYIN",
                    'service_api' =>"JIPAY",   
                ]);
            }

            $userId = $checkTxn[0]['userId'];
            $txnId = $checkTxn[0]['txnId'];//this is txnId (transID=782326476843)
            $referenceNumber = $resArr['agent_id'];//$checkTxn[0]['orderId']; 
            $ipAddress = $checkTxn[0]['IpAddress'];

            $getUser = User::where("id",$userId)->first();
            $getCommission = UserCharge::where('start_amount', '<=', $PayerAmount)->where('end_amount', '>=', $PayerAmount)->where('userId', '=', $userId)->first();
            if(empty($getCommission)){
                $charge = 3;
                $chargeType = "P";
            }else{
                $charge = $getCommission->payin_charge;
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
                'api'=>"JIPAY",
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
            $PayinModel->updatePayInDataByRefId($updatePayin,$referenceNumber);

            $datasend = ($response);//passed as it is coming
            
            //$datasend = json_encode($callbackRes);
            $callbackUrl = $getUser->payin_callback;//'https://api.paydexsolutions.in/api/v4/checkClientCallbackPayin';//
            
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

                ApiLog::create([
                    'txnId' => "callback sent to client: ".$referenceNumber,
                    'request' => "callback URL: ". $callbackUrl,
                    'response' => $datasend,  
                    'service' => "CALLBACK-PAYIN-CLIENT-SENT",
                    'service_api' =>"JIPAY",   
                ]);
            
                echo $response = curl_exec($curl);
                curl_close($curl);
            } else {
                ApiLog::create([
                    'txnId' => "",
                    'request' => "callback URL: ". $callbackUrl,
                    'response' => $datasend,  
                    'service' => "CALLBACK-PAYOUT-FAILED",
                    'service_api' =>"JIPAY",   
                    ]);
            }
        } else if(!empty($resArr) && $resArr['status'] == "FAILED") {
            $referenceNumber = $resArr['agent_id'];//this is reference Number
            $updatePayin = [
                "status"=>"FAILED",
            ];                  
            $PayinModel = new PayinModel();      
            $PayinModel->updatePayInDataByRefId($updatePayin,$referenceNumber);
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
        //We have to match $referenceNumber(for payin, RefNo will be JIP......) with orderId here
        $payin_details = PayinModel::where('orderId', $referenceNumber)->first();
        $remark = isset($payin_details->remark) ? $payin_details->remark : '';
        
        //if($remark != ''){
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

}
