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

class CallbackVouch extends Controller
{
    
    public function callBackData(Request $request){

        $jsonData = $request->json()->all();    
        ApiLog::create([
            'txnId' => "",
            'request' => "",
            'response' => json_encode($jsonData),  
            'service' => "CALLBACK-VOUCH",
            'service_api' =>"VOUCH",   
        ]);

        $code = $jsonData['code'];
        $payout_ref = $jsonData['payout_ref'];        
        $message = $jsonData['message'];
        $status = $jsonData['status'];
        
        $checkTxn = PayoutModel::where("txnId",$payout_ref)->get()->toArray();

        if(count($checkTxn) == 0){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Transation not found.',
            ];
            echo '{"status": 1,"statusDesc": "Failure"}';die;
            //return response()->json($responseData , 200); die;
        }

        $TxnStatus = $checkTxn[0]['status'];
        $TxnApi = $checkTxn[0]['api'];

        if($TxnApi != "VOUCH"){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Transation not found',
            ];
            //return response()->json($responseData , 200); die;
            echo '{"status": 1,"statusDesc": "Failure"}';die;
        }

        if($TxnStatus != "PENDING"){
            echo '{"status": 1,"statusDesc": "Failure"}';die;
        }

        if($TxnStatus == "SUCCESS"){

            $TxnUtr = $checkTxn[0]['utr'];
            $responseData = [
                "status" => "SUCCESS",
                "message" => "Transaction is Successful",
                "data" => array(
                    "payout_ref" => $payout_ref,
                    "bank_ref" => $TxnUtr
                ),
            ];
            echo '{"status": 0,"statusDesc":"success"}';
            //return response()->json($responseData , 200);
            die;
        }

        if($TxnStatus == "FAILED"){

            $responseData = [
                "status" => "FAILED",
                "message" => "Transaction is failed",
                "data" => array(
                    "payout_ref" => $payout_ref,
                    "bank_ref" => ""
                ),
            ];
            echo '{"status": 0,"statusDesc":"success"}';
            //return response()->json($responseData , 200);
           die;
        }

        $userId = $checkTxn[0]['userId'];
        $orderId = $checkTxn[0]['orderId'];
        $totalDeductAmount = $checkTxn[0]['totalAmount'];
        $userData  = User::find($userId);

        $payout_callback = $userData->payout_callback;
        
        if($status = "processed"){

            $rrn = $jsonData['bankref'];

            $statusMessage = "Transaction is Successful."; 
            $updateUser = [
                'status' => "SUCCESS"
            ];  
            $UserTransaction = new UserTransaction();      
            $UserTransaction->updateUserTxnData($updateUser,$payout_ref);

            $updatePayout = [
                "utr"=>$rrn,
                "status"=>"SUCCESS",
                "remark"=>$statusMessage
            ];                  
            $PayoutModel = new PayoutModel();      
            $PayoutModel->updatePayoutData($updatePayout,$payout_ref);

            $callbackData = [
                "status" => "SUCCESS",
                "message" => $statusMessage,
                "data" => array(
                    "payout_ref" => $orderId,
                    "bank_ref" => $rrn
                ),
            ];

            $sendCallbackData = json_encode($callbackData);

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $payout_callback,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$sendCallbackData,
            CURLOPT_HTTPHEADER => array(
                'Authorization: f1ebf003789c44677ad68cd4debaaa5d2d8dc2a9',
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            //echo $response;
            echo '{"status": 0,"statusDesc":"success"}';
            die;
        }


        if($status = "failed"){

            $openBal = $userData->wallet;

            $closeBal = $openBal + $totalDeductAmount;
            $UserInstance = new User();
            $UserInstance->addFund($userId,$totalDeductAmount);
            $remark = "Refund Amount Due To Payout Failure ";
            UserTransaction::create([
                'userId' => $userId,
                'txnId' => $payout_ref,
                'orderId' => $orderId,
                'type' => "CREDIT",
                'operator' => "REFUND",
                'openBalance'=>$openBal,
                'amount' => $totalDeductAmount,
                'walletBalance' =>$closeBal,  
                'credit' =>$totalDeductAmount, 
                'debit' =>0, 
                'remark' => $remark,   
                'api'=>"VOUCH",
                'status'=>"SUCCESS",
                'requestIp' => $ipAddress,
                "refundId"=>$payout_ref,    
                'created_by' => 1,
            ]);

            $statusMessage = "Transaction is failed."; 
            $updateUser = [
                'status' => "REFUNDED"
            ];  
            $UserTransaction = new UserTransaction();      
            $UserTransaction->updateUserTxnData($updateUser,$payout_ref);

            $updatePayout = [
                "status"=>"FAILED",
                "remark"=>$statusMessage
            ];                  
            $PayoutModel = new PayoutModel();      
            $PayoutModel->updatePayoutData($updatePayout,$payout_ref);

            $callbackData = [
                "status" => "FAILED",
                "message" => $statusMessage,
                "data" => array(
                    "payout_ref" => $orderId,
                    "bank_ref" => ""
                ),
            ];

            $sendCallbackData = json_encode($callbackData);

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $payout_callback,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$sendCallbackData,
            CURLOPT_HTTPHEADER => array(
                'Authorization: f1ebf003789c44677ad68cd4debaaa5d2d8dc2a9',
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            //echo $response;
            echo '{"status": 0,"statusDesc":"success"}';
            die;
        }
        
    }

    public function registerCallBackUrl()
    {

        $header = array(
            'Content-Type:application/json',
            'apikey:eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOiJkNjkwYTdmYS00YjQ4LTRiMDAtYmVlNi0xMmFkMWZlYTA5MDgiLCJuYW1lIjoiTWFwcHNpbmsgQWR2aXNvcnkgUHJpdmF0ZSBMaW1pdGVkIiwicmVnIjoiVEduU2NWbTk5eHBGSzhxMG5uMkwiLCJjb25maWciOiJTZW9zUEFZIiwiZW52IjoibGl2ZSIsImlhdCI6MTY5NTI5MDA4NH0.FP544qP1ymHNu0MXJA6wQl9xke3kl7JsZ4WSY1JUX0Y'
        );

        $url = "https://dashboard.seospay.in/api/v3/callback/Vouch";
        $timestamp = date("d/m/Y H:i:s a");
        $signature = "";
        $requestBody = [
            'webhook_endpoint_url'=>$url,
            'timestamp'=>$timestamp,
        ];

        $requestBodyJson = json_encode($requestBody, JSON_UNESCAPED_SLASHES);
        //print_r($requestBodyJson);
        $pem_private_key =  $pem_private_key = file_get_contents(asset('public/vouchpay.key'));
        ////$this->vouchpayModel->getPrivateKey();
        $encryptedData = openssl_sign($requestBodyJson, $binary_signature, $pem_private_key, OPENSSL_ALGO_SHA256);
        $binary_signature = base64_encode($binary_signature);  

        $finalRequestBody = [
            'webhook_endpoint_url'=>$url,
            'timestamp'=>$timestamp,
            'signature'=>$binary_signature
        ];

        $payload = json_encode($finalRequestBody);

        $curl = curl_init();        

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sim.iamvouched.com/v1/escrow/update_webhook_endpoint_url',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$payload,
        CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
   
    }

    public function testCallback(Request $request)
    {
        $jsonData = $request->json()->all();    
            ApiLog::create([
                'txnId' => "",
                'request' => "",
                'response' => json_encode($jsonData),  
                'service' => "CALLBACK-VOUCH-TEST",
                'service_api' =>"VOUCH",   
            ]);
            echo "get";
    }

}


?>