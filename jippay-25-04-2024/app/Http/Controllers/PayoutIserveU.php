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

class PayoutIserveU extends Controller
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

        $txn_id = rand(123121,990999).Carbon::now()->timestamp;
        $name = $request['name'];
        $accountNumber = $request['accountNumber'];
        $bankIfsc = $request['bankIfsc'];
        $mobileNumber = $request['mobileNumber'];
        $beneBankName = $request['beneBankName'];
        $referenceNumber = $request['referenceNumber'];
        $transferAmount = $request['transferAmount'];
        $transferMode = $request['transferMode'];

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
            'api'=>"ISERVEU",
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
            'api'=>"ISERVEU",
            'IpAddress' => $ipAddress,
        ]);

        $pincode = "201310";
        $customerName = $getUser->name;
        $customerMobileNumber = $getUser->mobile;
        $latitude = "19.07";
        $longitude = "72.88";        

        $request = [
            "BenificiaryName" => $name,
            "BenificiaryAccount" => $accountNumber,
            "BenificiaryIfsc" => $bankIfsc,
            "TransactionId" => $txn_id,
            "Amount" => (float)$transferAmount,            
            "Longitude" => $longitude,
            "Latitude" => $latitude
        ];       

        $payload = json_encode($request);

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.huntood.com/api/payout/v1/dotransaction',
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
            'txnId' => $txn_id,
            'request' => $payload,
            'response' => $response,  
            'service' => "PAYOUT",
            'service_api' =>"ISERVEU",   
        ]);

        $responseData = json_decode($response, true);         

        $status = $responseData['data']['status'];
        $statuscode = $responseData['responseCode'];
        $statusMessage = $responseData['message'];
        $transactionId = $responseData['data']['apiTransactionId'];        
        $clientReferenceNo = $responseData['data']['apiWalletTransactionId'];

        

        if($statuscode == 200 && $status == "FAILURE"){

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

        if($statuscode == 200 && $status == "SUCCESS") {
            $rrn = $responseData['data']['rrn'];

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
}
