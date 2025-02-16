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

class PayoutJipPay extends Controller
{
    public function doPayout(Request $request)
    {
        $jsonData = $request->json()->all();        
        $Authorization = $request->header('Authorization');
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'accountNumber' => 'required',
            'bankIfsc' => 'required',
            'mobileNumber' => 'required',
            'beneBankName' => 'required',
            'referenceNumber' => 'required|unique:payout_transactions,orderId',
            'transferAmount' => 'required',
            'email' => 'required',
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
            return response()->json($responseData , 200);die;
        }
        $bank_deactive = $getUser->bank_deactive;
        if($bank_deactive == 1){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Your API has been Deactivated by the Bank due to security reasons.',
            ];
            return response()->json($responseData , 200);die;
        }
        $tecnical_issue = $getUser->tecnical_issue;
        if($tecnical_issue == 1){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'We are facing technical issue from bank side.',
            ];
            return response()->json($responseData , 200);die;
        }

        $iserveu = $getUser->iserveu;
        if($iserveu != 1){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'This service is not active at this time. please use ICICI bank api.',
            ];
            return response()->json($responseData , 200);die;
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

        $txn_id = 'JIPAY'.rand(123121,990999).Carbon::now()->timestamp;
        $name = $request['name'];
        $accountNumber = $request['accountNumber'];
        $bankIfsc = $request['bankIfsc'];
        $mobileNumber = $request['mobileNumber'];
        $beneBankName = $request['beneBankName'];
        $referenceNumber = $request['referenceNumber'];
        $transferAmount = $request['transferAmount'];
        $email = $request['email'];
        $transferMode = 'IMPS';
        $bankName = $request['bankName'];

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
            'api'=>"JIPAY",
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
            'beneName' => $name,          
            'beneBank' => $beneBankName,
            'beneAccount' => $accountNumber,   
            'beneIfsc' => $bankIfsc,          
            'status' => "PENDING",
            'api'=>"JIPAY",
            'IpAddress' => $ipAddress,
        ]);

        $customerName = $getUser->name;
        $customerMobileNumber = $getUser->mobile;
         
        $key = 'KEY1e779794e9deb9376cbd';//fixed
        $token = '59444a2447b809659608b95a1';//fixed 

        $payload = "key=$key&token=$token&agent_id=$referenceNumber&amount=$transferAmount
        &name=$name&account=$accountNumber&ifsc=$bankIfsc&bank_name=$beneBankName
        &phone=$mobileNumber&email=$email";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://payout.paydexsolutions.com/prod/direct-payout");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "key=$key&token=$token&agent_id=$referenceNumber&amount=$transferAmount&name=$name&account=$accountNumber&ifsc=$bankIfsc&bank_name=$beneBankName&phone=$mobileNumber&email=$email");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        ApiLog::create([
            'txnId' => $txn_id,
            'request' => $payload,
            'response' => $response,  
            'service' => "PAYOUT",
            'service_api' =>"JIPAY",   
        ]);

        $responseData = json_decode($response, true);  
        $transactionId = '';   
        //echo '<pre>';print_r($responseData);die;    
        

        if($responseData['status'] == 'PROCESSING'){
            $responseData = [
                "status" => "PENDING",
                "message" => "Transaction is under process",
                "data" => array(
                    "payout_ref" => $referenceNumber,
                    "transaction_id" => $transactionId,
                    "bank_ref" => ""
                ),
            ];
        } else {
            $responseData = [
                "status" => "FAILED",
                "message" => "Transaction is failed",
                "data" => array(
                    "payout_ref" => $referenceNumber,
                    "transaction_id" => $transactionId,
                    "bank_ref" => ""
                ),
            ];
        }

        
        return response()->json($responseData , 200);
    }

    public function checkCallbackData()
    {
        //PAYOUT callback API
        //$response = file_get_contents('php://input');
        $response = json_encode($_POST);
        ApiLog::create([
            'txnId' => "",
            'request' => "",
            'response' => $response,
            'service' => "CALLBACK-PAYOUT",
            'service_api' =>"JIPAY",
        ]);

        $responseData = json_decode($response, TRUE);
        $utr = isset($responseData['utr']) ? $responseData['utr'] : '';
        $txnStatus = isset($responseData['status']) ? $responseData['status'] : '';
        $referenceNumber = isset($responseData['agent_id']) ? $responseData['agent_id'] : '';
        $transactionId = isset($responseData['txn_id']) ? $responseData['txn_id'] : '';//bank Trans ID

        $checkTxn = UserTransaction::where("orderId",$referenceNumber)->get()->toArray();
        if(!empty($checkTxn)){

            if($txnStatus == 'SUCCESS'){
                $updateData = [
                    'status' => $txnStatus
                ];  

                $UserTransaction = new UserTransaction();      
                $UserTransaction->updateUserOrderIdData($updateData,$referenceNumber);
                
                $updatePayout = [
                    "contactId"=>$transactionId,//it was random string generated by us, now it is updated as bank's txnid
                    "utr"=>$utr,
                    "status"=>$txnStatus,
                    "remark"=>''
                ];                  
                $PayoutModel = new PayoutModel();
                $PayoutModel->updatePayoutDataByOrderId($updatePayout,$referenceNumber);

            } else if(!empty($checkTxn) && $txnStatus == 'FAILED'){ 
                $openBal = $checkTxn[0]['openBalance'];
                $totalDeductAmount = $checkTxn[0]['debit'];
                $userId = $checkTxn[0]['userId'];
                $updateData = [
                    'walletBalance' =>$openBal,
                    'status' => "FAILED"
                ];  

                $UserTransaction = new UserTransaction();      
                $UserTransaction->updateUserOrderIdData($updateData,$referenceNumber);

                $updatePayout = [
                    "contactId"=>$transactionId,                
                    "status"=>"FAILED",
                    "remark"=>$statusMessage
                ];                  
                $PayoutModel = new PayoutModel();      
                $PayoutModel->updatePayoutDataByOrderId($updatePayout,$referenceNumber);

                $UserInstance = new User();
                $UserInstance->addFund($userId,$totalDeductAmount);
            }
        }  



       if($referenceNumber != ''){
            //client call back
             if(!empty($checkTxn)){
                $userData = User::findOrFail($checkTxn[0]['userId']);
                
                $sendCallbackData = ($response);//passed as it is coming

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
                    'service_api' =>"JIPAY",   
                ]);
            } 
        } else {
            ApiLog::create([
            'txnId' => "",
            'request' => "",
            'response' => $response,  
            'service' => "CALLBACK-PAYOUT-FAILED",
            'service_api' =>"JIPAY",   
            ]);
        }
    }

    function doPayoutCheckStatus(Request $request)
    {
        
        $jsonData = $request->json()->all();        
        $Authorization = $request->header('Authorization');
        $validator = Validator::make($request->all(), [
            'referenceNumber' => 'required'
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
        $referenceNumber = $jsonData['referenceNumber'];
        $key = 'KEY1e779794e9deb9376cbd';//fixed
        $token = '59444a2447b809659608b95a1';//fixed
        
        $payload = "key=$key&token=$token&agent_id=$referenceNumber";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://payout.paydexsolutions.com/prod/check-status");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "$payload");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        
        ApiLog::create([
            'txnId' => '',
            'request' => $payload,
            'response' => $response,  
            'service' => "GENERATEUPI",
            'service_api' =>"JIPAY-CHECKSTATUS",   
        ]);

        $responseData = json_decode($response,TRUE);
        $responseData = [
            "status" => "SUCCESS",
            "message" => "Transaction Check Status",
            "data" => array(
                "referenceNumber" => $responseData['agent_id'],
                "transaction_id" => $responseData['txn_id'],
                "utr" => $responseData['utr'],
                "status" => $responseData['status'],
                "amount" => $responseData['amount']
            ),
        ];
        return response()->json($responseData , 200);
        
    }

    public function checkClientCallbackPayout(Request $request) { 
        $callbackResponse = file_get_contents('php://input');
        //$response = urldecode($callbackResponse);
        ApiLog::create([
            'txnId' => "",
            'request' => "",
            'response' => $callbackResponse,  
            'service' => "CALLBACK-PAYOUT-CLIENT-RECEIVED",
            'service_api' =>"JIPAY",   
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
            'service_api' =>"JIPAY",   
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
}
