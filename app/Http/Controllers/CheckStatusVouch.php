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

class CheckStatusVouch extends Controller
{
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
            return response()->json($responseData, 422); die;
        }

        $getUser = User::where("user_token",$Authorization)->where("status",1)->where("api_status",1)->where("user_type",1)->first();
        if(empty($getUser)){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Invalid credentials',
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
        $checkTxn = PayoutModel::where("orderId",$referenceNumber)->where("userId",$userId)->where("api","VOUCH")->get()->toArray();
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

        if($TxnApi != "VOUCH"){
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
           //return response()->json($responseData , 200);
            //die;
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

        $header = array(
            'Content-Type:application/json',
            'apikey:eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOiJkNjkwYTdmYS00YjQ4LTRiMDAtYmVlNi0xMmFkMWZlYTA5MDgiLCJuYW1lIjoiTWFwcHNpbmsgQWR2aXNvcnkgUHJpdmF0ZSBMaW1pdGVkIiwicmVnIjoiVEduU2NWbTk5eHBGSzhxMG5uMkwiLCJjb25maWciOiJTZW9zUEFZIiwiZW52IjoibGl2ZSIsImlhdCI6MTY5NTI5MDA4NH0.FP544qP1ymHNu0MXJA6wQl9xke3kl7JsZ4WSY1JUX0Y'
        );
        
        $requestBody = ["payout_ref_arr"=>[$txnID]];
        $payload = json_encode($requestBody);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://sim.iamvouched.com/v1/escrow/get_payout_status',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $header
        ]);

        echo $response = curl_exec($curl);die;
        $err = curl_error($curl);
        curl_close($curl);

        ApiLog::create([
            'txnId' => $txnID,
            'request' => $payload,
            'response' => $response,  
            'service' => "CHECKSTATUS-VOUCH",
            'service_api' =>"VOUCH",   
        ]);

        $responseData = json_decode($response, true);

        if(empty($responseData['data'][$txnID])){
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

        if($responseData['data'][$txnID]['status'] == "processed")
        {
            $rrn = $responseData['data'][$txnID]['bankref'];
            $updatePayout = [
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
        }

        elseif($responseData['data'][$txnID]['status'] == "failed")
        {
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
                'api'=>"VOUCH",
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
        }else
        {
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
}


