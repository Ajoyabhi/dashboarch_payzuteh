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

class CallbackIserveU extends Controller
{
    
    public function callBackData(Request $request){

        $jsonData = $request->json()->all();    
        if(empty($jsonData)){
            echo '{"status": 1,"statusDesc": "Failure"}';die;
        }
        ApiLog::create([
            'txnId' => "",
            'request' => "",
            'response' => json_encode($jsonData),  
            'service' => "CALLBACK",
            'service_api' =>"ISERVEU",   
        ]);
        
        echo '{"status": 1,"statusDesc": "Success"}';die;
        die();

        $ClientRefID =  $jsonData['ClientRefID'];
        $checkTxn = PayoutModel::where("txnId",$ClientRefID)->get()->toArray();
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

        if($TxnApi != "ISERVEU"){
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
                    "payout_ref" => $referenceNumber,
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
                    "payout_ref" => $referenceNumber,
                    "bank_ref" => ""
                ),
            ];
            echo '{"status": 0,"statusDesc":"success"}';
            //return response()->json($responseData , 200);
            die;
        }

        $statusDesc = $jsonData['statusDesc'];
        $productCode = $jsonData['productCode'];
        $txnId = $jsonData['txnId'];
        $status = $jsonData['status'];
        
        if($status = "SUCCESS"){

            $rrn = $jsonData['rrn'];

            $updateUser = [
                'status' => "SUCCESS"
            ];  

            $UserTransaction = new UserTransaction();      
            $UserTransaction->updateUserTxnData($updateUser,$ClientRefID);

            $updatePayout = [
                "contactId"=>$transactionId,
                "utr"=>$rrn,
                "status"=>"SUCCESS",
                "remark"=>$statusDesc
            ];                  
            $PayoutModel = new PayoutModel();      
            $PayoutModel->updatePayoutData($updatePayout,$ClientRefID);

            echo '{"status": 0,"statusDesc":"success"}';
            die;
        }

        echo '{"status": 0,"statusDesc":"success"}';
        die;
        
    }
}


?>