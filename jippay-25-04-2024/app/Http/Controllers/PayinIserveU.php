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

class PayinIserveU extends Controller 
{
    public function generatePaymentLink(Request $request)
    {
        $jsonData = $request->json()->all(); 
           
        $Authorization = $request->header('Authorization');
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',           
            'referenceNumber' => 'required|unique:payin_transactions,orderId',
            'email' => 'required',
            'phone' => 'required',
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

        $txn_id = "PDX".rand(123121,990999).Carbon::now()->timestamp;

        $amount = $request['amount'];
        $name = $request['name'];
        $referenceNumber = $request['referenceNumber'];
        $email = $request['email'];
        $phone = $request['phone'];

        $request = [            
            "amount" => $request['amount'],
            "Name" => $request['name'],
            "ReferenceId" =>$txn_id,
            "Email" => $request['email'],
            "Phone" => $request['phone'],
        ];

        $payload = json_encode($request);

        $curl = curl_init();
   
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.huntood.com/api/DyupiV2/V4/GenerateUPI',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$payload,
          CURLOPT_HTTPHEADER => array(
            'IPAddress: 103.205.64.72',
            'AuthKey: 453838ae11a02598b0d54409c92f76f55aa7a2789830d7c1f2aa189c875b3923',
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        //echo $response;
        ApiLog::create([
            'txnId' => $txn_id,
            'request' => $payload,
            'response' => $response,  
            'service' => "GENERATEUPI",
            'service_api' =>"PINWALLET",   
        ]);

        $responseData = json_decode($response,TRUE);

        if($responseData['responseCode'] == 200 && $responseData['message'] == "SUCCESS")
        {
            
            $payLink = str_replace(' ', '', $responseData['data']['qr']);
            $walletTransactionId = $responseData['data']['walletTransactionId'];
            
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
            die;
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
        die;
    }

    function callBackData(Request $request)
    {
        $response = file_get_contents('php://input');
        ApiLog::create([
            'txnId' => "",
            'request' => "",
            'response' => $response,  
            'service' => "CALLBACK-PAYIN",
            'service_api' =>"PINWALLET",   
        ]);

        $resData = json_decode($response,TRUE);

        if($resData['event'] == "DynamicUPIQR")
        {
            if($resData['Data']['TxnStatus'] == "SUCCESS")
            {
                $PayerAmount = $resData['Data']['PayerAmount'];
                $WalletTransactionId = $resData['Data']['WalletTransactionId'];
                $BankRRN = $resData['Data']['BankRRN'];
                $ApiUserReferenceId = $resData['Data']['ApiUserReferenceId'];

                $checkTxn = PayinModel::where("txnId",$ApiUserReferenceId)->get()->toArray();

                if(count($checkTxn) == 0){
                    $responseData = [                
                        'status' => FALSE,
                        'error'=> TRUE,
                        'message' => 'Transation not found.',
                    ];
                    echo '{"status": 1,"statusDesc": "Failure"}';die;
                }

                $checkUtr = PayinModel::where("utr",$BankRRN)->get()->toArray();

                if(count($checkUtr) == 1){
                    $responseData = [                
                        'status' => FALSE,
                        'error'=> TRUE,
                        'message' => 'utr already found.',
                    ];
                    echo '{"status": 1,"statusDesc": "Failure"}';die;
                }

                $userId = $checkTxn[0]['userId'];
                $txnId = $checkTxn[0]['txnId'];
                $referenceNumber = $checkTxn[0]['orderId'];
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
                    'api'=>"PINWALLET",
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

                $callbackRes = [

                    'status' => "SUCCESS",
                    "PayerAmount" => $PayerAmount,
                    "PayerName" => "",
                    "TransactionId" => $txnId,
                    "PayerMobile" => "",
                    "BankRRN" => $BankRRN,
                    "PayerVA" => "",
                    "orderId" => $referenceNumber,
                ];
                
                $datasend = json_encode($callbackRes);
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
                
                    echo $response = curl_exec($curl);
                    curl_close($curl);
                }

            }
        }
    }

    public function checkStatus(Request $request)
    {
        
        $jsonData = $request->json()->all();        
        $Authorization = $request->header('Authorization');

        $validator = Validator::make($request->all(), [
            'referenceNumber' => 'required',
        ]);

        if($validator->fails()) {
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => $validator->errors(),
            ];
            return response()->json($responseData, 422);
        }

        $referenceNumber = $request['referenceNumber'];
        $checkTxn = PayinModel::where("orderId",$referenceNumber)->get()->toArray();

        if(count($checkTxn) == 0){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Transation not found.',
            ];
            echo '{"status": 1,"statusDesc": "Failure","message":"Transation not found."}';die;
        }

        $userId = $checkTxn[0]['userId'];
        $txnId = $checkTxn[0]['txnId'];
        $referenceNumber = $checkTxn[0]['orderId'];
        $ipAddress = $checkTxn[0]['IpAddress'];
        $txnStatus = $checkTxn[0]['status'];

        if($txnStatus != "PENDING")
        {
            echo '{"status": 1,"statusDesc": "Failure","message":"Transation already updated."}';die;
        }

        $request = [            
            "TransactionId" => $referenceNumber,           
        ];

        $payload = json_encode($request);

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.huntood.com/api/DyupiV2/V4/docheckstatus',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$payload,
          CURLOPT_HTTPHEADER => array(
            'IPAddress: 103.205.64.72',
            'AuthKey: 453838ae11a02598b0d54409c92f76f55aa7a2789830d7c1f2aa189c875b3923',
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);        
        curl_close($curl);

        ApiLog::create([
            'txnId' => $txnId,
            'request' => $payload,
            'response' => $response,  
            'service' => "CHECKSTATUS",
            'service_api' =>"PINWALLET",   
        ]);

        $responseData = json_decode($response,TRUE);

        if($responseData['responseCode'] == 400 && $responseData['message'] == "Transaction Not Found")
        {
            $updatePayin = [
                "status"=>"FAILED",
            ];                  
            $PayinModel = new PayinModel();      
            $PayinModel->updatePayInData($updatePayin,$txnId);

            $datasend = [
                'status' => "FAILED",
                "TransactionId" => $txnId,
                "orderId" => $referenceNumber,
            ];

            return response()->json($datasend , 200);
            die;
        }

        if($responseData['responseCode'] == 200 && $responseData['message'] == "SUCCESS")
        {
            $PayerAmount = $responseData['data']['PayerAmount'];
            $WalletTransactionId = $responseData['data']['WalletTransactionId'];
            $BankRRN = $responseData['data']['BankRRN'];
            $ApiUserReferenceId = $responseData['data']['ApiUserReferenceId'];

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
                    'api'=>"PINWALLET",
                    'requestIp' => "",          
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

                $callbackRes = [

                    'status' => "SUCCESS",
                    "PayerAmount" => $PayerAmount,
                    "PayerName" => "",
                    "TransactionId" => $txnId,
                    "PayerMobile" => "",
                    "BankRRN" => $BankRRN,
                    "PayerVA" => "",
                    "orderId" => $referenceNumber,
                ];
                
                $datasend = json_encode($callbackRes);
                return response()->json($datasend , 200);
                die;
        }

    }


}
