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
use App\Models\PayoutModel;
use App\Models\ApiLog;

class PayoutHaodaPay extends Controller
{
    public function doPayout(Request $request)
    {
        $jsonData = $request->json()->all();
        $Authorization = $request->header('Authorization');

        $validator = Validator::make($request->all(), [
            'beneficiary_account_number' => 'required',
            'beneficiary_bank_ifsc' => 'required',
            'beneficiary_bank_name' => 'required',
            'beneficiary_name' => 'required',
            'payment_mode' => 'required',
            'amount' => 'required',
            'reference' => 'required|unique:payout_transactions,orderId',
        ]);

        if($validator->fails()) {
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => $validator->errors(),
            ];
            return response()->json($responseData, 422);
        }
        
        $getUser = User::where("user_token",$Authorization)->where("status",1)->whereIn('user_type', ['1', '4'])->first();
        if(empty($getUser)){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Invalid credentials',
            ];
            return response()->json($responseData , 401);
        }
        $userId = $getUser->id;
        
        if($getUser->payout_status == 1){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Your settlement is preparing. Please wait while updated !',
            ];
            return response()->json($responseData , 200);
        }
        
        $api_status = $getUser->api_status;
        if($api_status == 0){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'We are taking downtime due to some technical issues. Please wait till further update',
            ];
            return response()->json($responseData , 200);
        }
        $bank_deactive = $getUser->bank_deactive;
        if($bank_deactive == 1){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Your API has been Deactivated by the Bank due to security reasons.',
            ];
            return response()->json($responseData , 200);
        }
        $tecnical_issue = $getUser->tecnical_issue;
        if($tecnical_issue == 1){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'We are facing technical issue from bank side.',
            ];
            return response()->json($responseData , 200);
        }

        $iserveu = $getUser->iserveu;
        if($iserveu != 1){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'This service is not active at this time. please use ICICI bank api.',
            ];
            return response()->json($responseData , 200);
        }

        $ipAddress = $request->ip();
        /* $checkIp = UserIp::where(['userId'=>$userId,'ipAddress'=>$ipAddress])->first(); 
        if(empty($checkIp)){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'your ip is not whitlisted, requested ip is '.$ipAddress,
            ];
            return response()->json($responseData , 401);
        } */

        $txn_id = "NEST".rand(123121,990999).Carbon::now()->timestamp;

        $fullname = $request['beneficiary_name'];
        $accountNumber = $request['beneficiary_account_number'];
        $bankIfsc = $request['beneficiary_bank_ifsc'];
        $beneBankName = $request['beneficiary_bank_name'];
        $referenceNumber = $request['reference'];
        $transferAmount = $request['amount'];
        $transferMode = $request['payment_mode'];

        $getCommission = UserCharge::where('start_amount', '<=', $transferAmount)->where('end_amount', '>=', $transferAmount)->where('userId', '=', $userId)->first();
        if(empty($getCommission)){
            $charge = 20;
            $chargeType = "F";
        }else{
            $charge = $getCommission->payout_charge;
            $chargeType = $getCommission->payout_charge_type;
        }
        $gst = 18;
        if($chargeType == "F"){
            $totalCharge = $charge;
            $totalGst = ($totalCharge*$gst)/100;
        }else if($chargeType == "P"){
            $totalCharge = ($transferAmount*$charge)/100;
            $totalGst = ($totalCharge*$gst)/100;
        }
        $totalDeductAmount = $transferAmount +$totalCharge+$totalGst;
        $openBal = $getUser->wallet;
        $lien =$getUser->lien;
        $rolling_reserve = $getUser->rolling_reserve;
        $closeBal = $openBal - $totalDeductAmount;                    
        $checkAmount = $totalDeductAmount+$lien+$rolling_reserve;

        if($openBal == 0 || $openBal < $checkAmount){           
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Insufficient fund',
            ];
            return response()->json($responseData , 200);
        }
        
        $UserInstance = new User();
        $UserInstance->deductFund($userId,$totalDeductAmount);

        $remark = "Money Transfer Via Payout ";
        UserTransaction::create([
            'userId' => $userId,
            'txnId' => $txn_id,
            'orderId' => $referenceNumber,
            'type' => "DEBIT",  
            'operator' => "PAYOUT",
            'openBalance' =>$openBal, 
            'amount' => $totalDeductAmount,
            'walletBalance' =>$closeBal,
            'credit' =>0, 
            'debit' =>$totalDeductAmount,
            "status" => "PENDING",
            'remark' => $remark,   
            'api'=>"NEST-PAY",
            'requestIp' => $ipAddress,          
            'created_by' => $userId,
        ]);

        PayoutModel::create([
            'userId' => $userId,
            'txnId' => $txn_id,
            'orderId' => $referenceNumber,
            'amount' => $transferAmount,
            'charge' =>$totalCharge,   
            'gst'=>$totalGst,
            'totalAmount' => $totalDeductAmount,   
            'mode'=>$transferMode,
            'beneName' => $fullname,          
            'beneBank' => $beneBankName,
            'beneAccount' => $accountNumber,   
            'beneIfsc' => $bankIfsc,          
            'status' => "PENDING",
            'api'=>"NEST-PAY",
            'IpAddress' => $ipAddress,
        ]);

        $request = [
            "beneficiary_account_number" => $request['beneficiary_account_number'],
            "beneficiary_bank_ifsc" => $request['beneficiary_bank_ifsc'],
            "beneficiary_bank_name" => $request['beneficiary_bank_name'],
            "beneficiary_name" => $request['beneficiary_name'],
            "payment_mode" => $request['payment_mode'],
            "amount" => $request['amount'],
            "reference" => $request['reference'],
        ]; 
        $authNestPayToken = 'c39175eb696c72825b4e320295c8a739a894534b';

        $payload = json_encode($request);
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.nestpay.in/api/v6/doPayout',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$payload,
        CURLOPT_HTTPHEADER => array(
            "Authorization: $authNestPayToken",
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        ApiLog::create([
            'txnId' => $txn_id,
            'request' => $payload,
            'response' => $response,  
            'service' => "PAYOUT",
            'service_api' =>"HAODA",   
        ]);

        $responseData = json_decode($response, true);  
        //echo '<pre>';print_r($responseData);die;
        //{"status":"Processing","message":"Kindly allow some time for the payout to process","data":{"payout_ref":"24FEB10381653","payout_id":"H42032404180508000731143","bank_ref":""}}
        if($responseData['status'] == false && $responseData['error'] == true) {
            return response()->json($responseData , 200);
        } else if($responseData['status'] == "Processing") {
            return response()->json($responseData , 200);
        }

        $responseData = [
            "status" => "Processing",
            "message" => "Kindly allow some time for the payout to process",
            "data" => array(
                "payout_ref" => $referenceNumber,
                "payout_id" => $responseData['payout_id'],
                "bank_ref" => ""
            ),
        ];
        return response()->json($responseData , 200);

        die("END");
        
        
        echo '<pre>';print_r($responseData);die;
        //echo $response;die;
        $status = $responseData['status'];
        $statuscode = $responseData['code'];
        $statusMessage = $responseData['mess'];
        $transactionId = $txn_id;        

        $dataStatus = isset($responseData['data']['txn_status']) ? $responseData['data']['txn_status'] : '';

        if($dataStatus == 'PENDING'){
        $msg = isset($responseData['data']['txn_desc']) ? $responseData['data']['txn_desc'] : '';
            $responseData = [
                "status" => "PENDING",
                "message" => $msg,
                "data" => $responseData['data'],
            ];
            return response()->json($responseData , 200);
        }

        die;

        if($statuscode == "ERR" && $status == "failed"){

            $updateUser = [
                'walletBalance' =>$openBal,
                'status' => "FAILED"
            ];  

            $UserTransaction = new UserTransaction();      
            $UserTransaction->updateUserTxnData($updateUser,$txn_id);

            $updatePayout = [
                "contactId"=>$transactionId,                
                "status"=>"FAILED",
                "remark"=>$statusMessage
            ];                  
            $PayoutModel = new PayoutModel();      
            $PayoutModel->updatePayoutData($updatePayout,$txn_id);

            //$user = User::findOrFail($userId);
            $UserInstance = new User();
            $UserInstance->addFund($userId,$totalDeductAmount);

            $responseData = [
                "status" => "FAILED",
                "message" => $statusMessage,
                "data" => array(
                    "payout_ref" => $referenceNumber,
                    "bank_ref" => ""
                ),
            ];
            return response()->json($responseData , 200);
            die;
        }

        if($statuscode == "TXN" && $status == "success") {
            $rrn = $responseData['data']['RRN'];

            $updateUser = [
                'status' => "SUCCESS"
            ];  

            $UserTransaction = new UserTransaction();      
            $UserTransaction->updateUserTxnData($updateUser,$txn_id);

            $updatePayout = [
                "contactId"=>$transactionId,
                "utr"=>$rrn,
                "status"=>"SUCCESS",
                "remark"=>$statusMessage
            ];                  
            $PayoutModel = new PayoutModel();      
            $PayoutModel->updatePayoutData($updatePayout,$txn_id);

            $responseData = [
                "status" => "SUCCESS",
                "message" => $statusMessage,
                "data" => array(
                    "payout_ref" => $referenceNumber,
                    "bank_ref" => $rrn
                ),
            ];
            return response()->json($responseData , 200);
            die;
        }

        $responseData = [
            "status" => "PENDING",
            "message" => "Transaction is under process",
            "data" => array(
                "payout_ref" => $referenceNumber,
                "bank_ref" => ""
            ),
        ];
        return response()->json($responseData , 200);
        die;
    }

    public function checkCallbackData()
    {
        $response = file_get_contents('php://input');
        
        ApiLog::create([
            'txnId' => "",
            'request' => "",
            'response' => $response,  
            'service' => "CALLBACK-PAYOUT",
            'service_api' =>"HAODA",
        ]); 

        $responseData = json_decode($response, TRUE);

        if(isset($responseData) && $responseData['status'] == 'SUCCESS'){
            $utr = isset($responseData['data']['UTR']) ? $responseData['data']['UTR'] : '';
            // $transactionId = isset($responseData['data']['OrderID']) ? $responseData['data']['OrderID'] : '';
            $txnStatus = isset($responseData['status']) ? $responseData['status'] : 'FAILED';
            $transactionId = isset($responseData['data']['payout_id']) ? $responseData['data']['payout_id'] : '';
            $requestId = isset($responseData['data']['reference']) ? $responseData['data']['reference'] : '';//input referenceNumber
            $statusMessage = isset($responseData['data']['remarks']) ? $responseData['data']['remarks'] : '';

            $checkTxn = UserTransaction::where("orderId",$requestId)->get()->toArray();
            if(!empty($checkTxn)){

                if($txnStatus == 'SUCCESS'){
                    $updateData = [
                        'status' => "SUCCESS"
                    ];  

                    $UserTransaction = new UserTransaction();      
                    $UserTransaction->updateUserOrderIdData($updateData,$requestId);

                    $updatePayout = [
                        "contactId"=>$transactionId,//it was random string generated by us, now it is updated as bank's txnid
                        "utr"=>$utr,
                        "status"=>"SUCCESS",
                        "remark"=>$statusMessage
                    ];                  
                    $PayoutModel = new PayoutModel();
                    $PayoutModel->updatePayoutDataByOrderId($updatePayout,$requestId);

                    //
                } else if(!empty($checkTxn) && $txnStatus == 'FAILED'){ 
                    $openBal = $checkTxn[0]['openBalance'];
                    $totalDeductAmount = $checkTxn[0]['debit'];
                    $userId = $checkTxn[0]['userId'];
                    $updateData = [
                        'walletBalance' =>$openBal,
                        'status' => "FAILED"
                    ];  

                    $UserTransaction = new UserTransaction(); 
                    $UserTransaction->updateUserTxnDataByOrderId($updateData,$requestId);

                    $updatePayout = [
                        "contactId"=>$transactionId,                
                        "status"=>"FAILED",
                        "remark"=>$statusMessage
                    ];                  
                    $PayoutModel = new PayoutModel();      
                    $PayoutModel->updatePayoutDataByOrderId($updatePayout,$requestId);

                    $UserInstance = new User();
                    $UserInstance->addFund($userId,$totalDeductAmount);
                }
            }  



        if($requestId != ''){
                //client call back
                if(!empty($checkTxn)){
                    $userData = User::findOrFail($checkTxn[0]['userId']);
                    
                    $sendCallbackData = $response;//passed as it is coming

                //$datasend = json_encode($callbackRes);
                $callbackUrl = $userData->payout_callback;//'https://api.paydexsolutions.in/api/v4/checkClientCallbackPayout';//
            
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
                    CURLOPT_POSTFIELDS =>$sendCallbackData,
                    CURLOPT_HTTPHEADER => array(
                        //'Authorization: f1ebf003789c44677ad68cd4debaaa5d2d8dc2a9',
                        'Content-Type: application/json'
                    ),
                    ));
            
                    $response = curl_exec($curl);
                    curl_close($curl);

                    ApiLog::create([
                        'txnId' => "",
                        'request' => "callback URL: ". $callbackUrl,
                        'response' => $sendCallbackData,  
                        'service' => "CALLBACK-PAYOUT-CLIENT-SENT",
                        'service_api' =>"NESTPAY",   
                    ]);
                } 
            } else {
                ApiLog::create([
                'txnId' => "",
                'request' => "",
                'response' => $response,  
                'service' => "CALLBACK-PAYOUT-FAILED",
                'service_api' =>"NESTPAY",   
                ]);
            }
        }

       

        
    }

    public function checkClientCallbackPayout(Request $request) { 
        $callbackResponse = file_get_contents('php://input');
        //$response = urldecode($callbackResponse);
        ApiLog::create([
            'txnId' => "",
            'request' => "",
            'response' => $callbackResponse,  
            'service' => "CALLBACK-PAYOUT-CLIENT-RECEIVED",
            'service_api' =>"HAODA",   
        ]);
    }

    public function callBackData()
    {
        $response = file_get_contents('php://input');
        
        ApiLog::create([
            'txnId' => "",
            'request' => "",
            'response' => $response,  
            'service' => "CALLBACK-PAYOUT",
            'service_api' =>"HAODA",   
        ]);
        
        $responseData = json_decode($response, TRUE);
        $rrn = isset($responseData['data']['RRN']) ? $responseData['data']['RRN'] : '';
        $transactionId = isset($responseData['data']['OrderID']) ? $responseData['data']['OrderID'] : '';
        if($transactionId != ''){
            $updatePayout = ["utr"=>$rrn];                  
            $PayoutModel = new PayoutModel();
            $PayoutModel->updatePayoutData($updatePayout,$transactionId);
        }
    }

    public function sendPaymentIMPS($amount,$acc,$ifsc,$orderid,$name,$mobile){
        $curl = curl_init();
        $json_string = '{
            "APIID": "API1018",
            "Token": "48d96fec-e1db-4f29-b5be-dd871335ac80",
            "MethodName": "payout",
            "OrderID": "'.$orderid.'",
            "Name": "'.$name.'",
            "Amount": "'.$amount.'",
            "number":"'.$acc.'",
            "ifsc":"'.$ifsc.'",
            "PaymentType":"IMPS",
            "customer_mobile":"9600849730",
            "CustomerMobileNo":"'.$mobile.'"
        }';
        curl_setopt_array($curl, [
          CURLOPT_URL => "https://ibrpay.com/api/PayoutLive.aspx",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $json_string,
          CURLOPT_HTTPHEADER => [
            "Accept: */*",
            "Content-Type: application/json"
          ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        if ($err) {
            return array('status'=>false,'data'=>json_decode($err));
        } else {
            return array('status'=>true,'data'=>json_decode($response));
        }
    }

    function doPayoutCheckStatus(Request $request)
    {
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
        $referenceNumber = $jsonData['referenceNumber'];
        $payout_details = PayoutModel::where('orderId', $referenceNumber)->first();
        $remark = isset($payout_details->remark) ? $payout_details->remark : '';
        
        //if($remark != ''){
        if(!empty($payout_details)){
            $responseData = [
                "status" => $payout_details->status,//"SUCCESS",
                "message" => $remark,
                "data" => array(
                    "payout_ref" => $referenceNumber,
                    "bank_ref" => $payout_details->utr
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

        //START
        /* 
        $clientId = 'HRNsgFzJTE4203';
        $collectionClientId = 'Mbd9vk35fy240311034913';
        $PayoutClientId = 'Mbd9vk35fy240311034913';
        $request = [ "order_id" => $jsonData['referenceNumber'] ];
        $payload = json_encode($request);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://kepler.haodapayments.com/api/v1/payout/checkstatus',
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

}
