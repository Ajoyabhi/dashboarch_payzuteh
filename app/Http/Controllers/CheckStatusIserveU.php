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

class CheckStatusIserveU extends Controller
{
    
    public function checkStatus(Request $request){

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
            return response()->json($responseData, 422); die;
        }

        $getUser = User::where("user_token",$Authorization)->where("status",1)->where("api_status",1)->where("user_type",1)->first();
        if(empty($getUser)){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Invalid credentials.',
            ];
            return response()->json($responseData , 401); die;
        }

        $userId = $getUser->id;
        $ipAddress = $request->ip();
        $checkIp = UserIp::where(['userId'=>$userId,'ipAddress'=>$ipAddress])->first();
        if(empty($checkIp)){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'your ip is not whitlisted, requested ip is '.$ipAddress,
            ];
            //return response()->json($responseData , 401);
        }
        $referenceNumber = $request['referenceNumber'];
        $checkTxn = PayoutModel::where("orderId",$referenceNumber)->where("userId",$userId)->where("api","ISERVEU")->get()->toArray();
        if(count($checkTxn) == 0){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Transation not found.',
            ];
            return response()->json($responseData , 200); die;
        }

        $TxnStatus = $checkTxn[0]['status'];
        $TxnApi = $checkTxn[0]['api'];

        if($TxnApi != "ISERVEU"){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Transation not found',
            ];
            return response()->json($responseData , 200); die;
        }

        if($TxnStatus == "SUCCESS"){

            $TxnUtr = $checkTxn[0]['utr'];
            $responseData = [
                "status" => "SUCCESS",
                "message" => "Transaction is Successful",
                "data" => array(
                    "payout_ref" => $referenceNumber,
                    "bank_ref" => $TxnUtr
                ),
            ];
            return response()->json($responseData , 200);
            die;
        }

        if($TxnStatus == "FAILED"){

            $responseData = [
                "status" => "FAILED",
                "message" => "Transaction is failed",
                "data" => array(
                    "payout_ref" => $referenceNumber,
                    "bank_ref" => ""
                ),
            ];
            return response()->json($responseData , 200);
            die;
        }

        if($TxnStatus == "REFUNDED"){

            $responseData = [
                "status" => "FAILED",
                "message" => "Transaction is failed",
                "data" => array(
                    "payout_ref" => $referenceNumber,
                    "bank_ref" => ""
                ),
            ];
            return response()->json($responseData , 200);
            die;
        }
                
        $txnID = $checkTxn[0]['txnId'];
        $txnContactId = $checkTxn[0]['contactId'];
        $timestamp = $checkTxn[0]['created_at'];
        $totalDeductAmount = $checkTxn[0]['totalAmount'];

        $quesyOperation = "Cashout_addbank_status";
        $startDate = Carbon::parse($timestamp)->format('Y-m-d');
        $endDate = Carbon::parse($timestamp)->format('Y-m-d');

        $request = [
            "TransactionId" => $txnID
        ];

        echo $payload = json_encode($request);

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.huntood.com/api/payout/docheckstatus',
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
        
        echo $response = curl_exec($curl);die;
        
        curl_close($curl);

        ApiLog::create([
            'txnId' => $txnID,
            'request' => $payload,
            'response' => $response,  
            'service' => "CHECKSTATUS",
            'service_api' =>"PINWALLET",   
        ]);

        if (empty($response)) {
            $response = [
                'status'   => 400,
                'error'    => true,
                'messages' => 'Something went wrong'
            ];
            return $this->respond($response, 400);
        }

        $responseData = json_decode($response, true);

        if (isset($responseData['status']) && $responseData['status'] == 1) {
            return response()->json($response , 400); die;
        }

        if (isset($responseData['status']) && $responseData['status'] == 0) {
            return response()->json($response , 400); die;
        }

        $status = @$responseData['results'][0]['status'];
        $rrn = @$responseData['results'][0]['rrn'];
        $contact = @$responseData['results'][0]['txnId'];
        $clientreferenceid = @$responseData['results'][0]['clientreferenceid'];
        $Client_ref_id =str_replace("#", "", $clientreferenceid);
        $contactId = str_replace("#", "", $contact);

        if($status == "SUCCESS"){

            $updatePayout = [
                "contactId"=>$contactId,
                "utr"=>$rrn,
                "status"=>"SUCCESS",
            ];                  
            $PayoutModel = new PayoutModel();      
            $PayoutModel->updatePayoutData($updatePayout,$txnID);

            $updateUser = [
                'status' => "SUCCESS"
            ];  

            $UserTransaction = new UserTransaction();      
            $UserTransaction->updateUserTxnDataByOrderid($updateUser,$referenceNumber);

            $responseData = [
                "status" => "SUCCESS",
                "message" => "Transaction Successful",
                "data" => array(
                    "payout_ref" => $referenceNumber,
                    "bank_ref" => $rrn
                ),
            ];
            return response()->json($responseData , 200);
            die;

        }else if($status == "FAILED"){


            $updateUser = [
                'status' => "REFUNDED"
            ];  

            $UserTransaction = new UserTransaction();      
            $UserTransaction->updateUserTxnDataByOrderid($updateUser,$referenceNumber);
            
            $openBal = $getUser->wallet;

            $closeBal = $openBal + $totalDeductAmount;
            $UserInstance = new User();
            $UserInstance->addFund($userId,$totalDeductAmount);
            $remark = "Refund Amount Due To Payout Failure ";
            UserTransaction::create([
                'userId' => $userId,
                'txnId' => $txnID,
                'orderId' => $referenceNumber,
                'type' => "CREDIT",
                'operator' => "REFUND",
                'openBalance'=>$openBal,
                'amount' => $totalDeductAmount,
                'walletBalance' =>$closeBal,  
                'credit' =>$totalDeductAmount, 
                'debit' =>0, 
                'remark' => $remark,   
                'api'=>"ISERVEU",
                'status'=>"REFUNDED",
                'requestIp' => $ipAddress,
                "refundId"=>$txnID,    
                'created_by' => 1,
            ]);

            $updatePayout = [
                "contactId"=>"",                
                "status"=>"FAILED",
            ];                  
            $PayoutModel = new PayoutModel();      
            $PayoutModel->updatePayoutData($updatePayout,$txnID);

            

            $statusMessage = "Transaction is failed";
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

        }else{

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

    public function getUserBalance(Request $request)
    {
        $jsonData = $request->json()->all();        
        $Authorization = $request->header('Authorization');

        $getUser = User::where("user_token",$Authorization)->where("status",1)->where("api_status",1)->where("user_type",1)->first();
        if(empty($getUser)){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Invalid credentials',
            ];
            return response()->json($responseData , 401,); die;
        }

        $userId = $getUser->id;
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

        $walletBal = $getUser->wallet;
        $responseData = [
            "status" => "SUCCESS",
            "message" => "Transaction is Successful",
            "data" => array(
                "balance" => $walletBal,
            ),
        ];
        return response()->json($responseData , 200,[],JSON_UNESCAPED_SLASHES);
        die;
    }


}


?>