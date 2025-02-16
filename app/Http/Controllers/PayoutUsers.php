<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Bank;
use App\Models\UserCharge;
use App\Models\UserIp;
use App\Models\UserTransaction;
use App\Models\WalletTopup;
use App\Models\PlatformCharge;
use App\Models\PayoutModel;
use App\Models\PayinModel;
use App\Models\PayoutList;
use App\Models\PaymentRequest;

class PayoutUsers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $usertransaction = UserTransaction::where('userId',$userId)->orderBy('id','desc')->limit(5)->get();  
        $totalTrades = UserTransaction::where('userId',$userId)->count();
        $usersBalances = $user->wallet;
        $today = Carbon::today();
        $status = ['SUCCESS','PENDING','PROCESSING'];


        $todayPayout = PayoutModel::whereDate('created_at', $today)->whereIn('status',$status)->where('userId',$userId)->sum('amount');
        $totalPayout = PayoutModel::whereIn('status',$status)->where('userId',$userId)->where('userId',$userId)->sum('amount');
        //$todayTopUp = WalletTopup::whereDate('created_at', $today)->where('userId',$userId)->where('status',"APPROVED")->sum('amount');
        //$totalTopUp = WalletTopup::where('status',"APPROVED")->where('userId',$userId)->sum('amount');
        $todayPayin = PayinModel::whereDate('created_at', $today)->where('userId',$userId)->whereIn('status',$status)->sum('amount');
        $totalPayin = PayinModel::whereIn('status',$status)->where('userId',$userId)->sum('amount');

        $todayPayinTotal = PayinModel::selectRaw('sum(charge + gst) as total')->where('userId', $userId)->whereDate('created_at', $today)->whereIn('status',$status)->first();
        $todayPayoutTotal = PayoutModel::selectRaw('sum(charge + gst) as total')->where('userId', $userId)->whereDate('created_at', $today)->whereIn('status',$status)->first();
        $todayProfit = round(($todayPayinTotal->total+$todayPayoutTotal->total), 2);
        $payinTotal = PayinModel::selectRaw('sum(charge + gst) as total')->where('userId', $userId)->whereIn('status',$status)->first();
        $payoutTotal = PayoutModel::selectRaw('sum(charge + gst) as total')->where('userId', $userId)->whereIn('status',$status)->first();
        $totalProfit = round(($payinTotal->total+$payoutTotal->total), 2);
        //Dashboard Changes 13 Mar 24 END
        $data = ['labels' => ['Payin', 'Payout'],'todayData' => [$todayPayout, $todayPayin],'totalData' => [$totalPayin, $totalPayout]];
        return view('payout-user/dashboard',compact('data','usertransaction','usersBalances','totalTrades','totalPayin','totalPayout','todayPayout','todayPayin','todayProfit','totalProfit', 'user'));
    }

    function doPayin()
    {
        return view('payout-user/payin');
    }

    public function doPayout()
    {
        $data = [];
        $user = Auth::user();
        $data['user'] = $user;
        $data['banks'] = Bank::where('user_id',$user->id)->get();
        return view('payout-user/payout',$data);
    }

    public function storePayout(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'amount' => 'required|numeric',
            'payment_type' => 'required',
            'ref_number' => 'required'
        ]);

        $requestArr = $request->all();
        $requested_amount = trim($requestArr['amount']);
        if($requested_amount <= $user->settlement && $user->settlement != 0) {
            if($requested_amount >= 100) {
                $bank_details = Bank::where('id',$requestArr['payment_type'])
                                    ->where('user_id',$user->id)->first();
                $bank_data = new PayoutList([
                    'user_id' => $user->id,
                    'wallet_bal' => $user->wallet,
                    'name' => $user->name,
                    'ref_number' => trim($requestArr['ref_number']),
                    'description' => isset($requestArr['description']) ? $requestArr['description'] : "",
                    'amount' => trim($requestArr['amount']), 
                    'bank_id' => $requestArr['payment_type'], 
                    'cus_name' => $bank_details->cus_name,
                    'accountNumber' => $bank_details->acc_number,
                    'bankIfsc' => $bank_details->ifsc_code,
                    'mobileNumber' => $bank_details->mobile_no,
                    'beneBankName' => $bank_details->bank_name,
                    'transferMode' => $bank_details->payment_type,
                    'status' => 0, 
                    'created_at' => Carbon::now(),      
                    'updated_at' => NULL, 
                ]);
               
                if($bank_data->save()) {
                    return redirect()->route('payout-user/dopayout')->with('success', 'Thank you for your Payout Request. We will take action soon.');
                } else {
                    return redirect()->route('payout-user/dopayout')->with('error', 'Unable to take Payout Request! Please try again leter.');
                }
            } else {
                return redirect()->route('payout-user/dopayout')->with('error', 'Minimum withdraw balance should be 100 or more.');
            }
        } 
        else {
            return redirect()->route('payout-user/dopayout')->with('error', 'You have not sufficient amount to payout.');
        }
    }

    public function bankList()
    {
        $data = [];
        $user = Auth::user();
        $data['banks'] = Bank::where('user_id',$user->id)->get();
        return view('payout-user/banklist',$data);
    }

    public function addBank()
    {
        return view('payout-user/addbank');
    }

    public function storeBank(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'cus_name' => 'required',
            'acc_number' => 'required|unique:banks,acc_number',
            'mobile_no' => 'required',
            'ifsc_code' => 'required',
            'pincode' => 'required|regex:/^\d{6}$/',
            'bank_name' => 'required',
            'payment_type' => 'required'
        ]);

        $requestArr = $request->all();
        $bank_data = new Bank([
            'user_id' => $user->id,
            'cus_name' => strtoupper($requestArr['cus_name']),
            'acc_number' => $requestArr['acc_number'],
            'mobile_no' => $requestArr['mobile_no'],
            'ifsc_code' => strtoupper($requestArr['ifsc_code']),
            'pincode' => $requestArr['pincode'],
            'bank_name' => $requestArr['bank_name'],
            'payment_type' => $requestArr['payment_type'], 
            'created_at' => Carbon::now(),      
            'updated_at' => NULL, 
        ]);
        if($bank_data->save()) {
            return redirect()->route('user.addBank')->with('success', 'Bank has been added successfully.');
        } else {
            return redirect()->route('user.addBank')->with('error', 'Unable to add bank!');
        }
    }

    public function editBank(Bank $bank)
    {
        $data = [];
        $data['bank'] = $bank;
        return view('user.editbank',$data);
    }

    public function updateBank(Request $request, Bank $bank)
    {
        $user = Auth::user();
        $this->validate($request, [
            'cus_name' => 'required',
            'acc_number' => 'required',
            'mobile_no' => 'required',
            'ifsc_code' => 'required',
            'pincode' => 'required|regex:/^\d{6}$/',
            'bank_name' => 'required',
            'payment_type' => 'required'
        ]);

        $requestArr = $request->all();

        $bank->user_id = $user->id;
        $bank->cus_name = strtoupper($requestArr['cus_name']);
        $bank->acc_number = $requestArr['acc_number'];
        $bank->mobile_no = $requestArr['mobile_no'];
        $bank->ifsc_code = strtoupper($requestArr['ifsc_code']);
        $bank->pincode = $requestArr['pincode'];
        $bank->bank_name = $requestArr['bank_name'];
        $bank->payment_type = $requestArr['payment_type'];
        $bank->updated_at = Carbon::now(); 

        if($bank->save()) {
            return redirect()->route('payout-user.editBank',$bank->id)->with('success', 'Bank has been updated successfully.');
        } else {
            return redirect()->route('payout-user.editBank',$bank->id)->with('error', 'Unable to update bank!');
        }
    }

    public function deleteBank(Bank $bank)
    {
        $bank->delete();
        return redirect()->route('payout-user/bankList')->with('success', 'Bank has been deleted successfully.');
    }

    public function changePassword(Request $request)
    {
        $data['content'] = "";
        if($_POST){
            $request->validate([
                'oldpassword' => 'required',
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required',
            ]);

            $user = Auth::user();

            if (Hash::check($request->oldpassword, $user->password)) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);

                return redirect()->route('logout')->with('success', 'Password has been changed successfully.Please login');
            } else {
                return redirect()->back()->withErrors(['oldpassword' => 'Current password is incorrect.']);
            }
        }
        return view('payout-user/changepassword',compact('data'));
    }

    function viewProfile()
    {
        return view('payout-user/view-profile');
    }

    public function devSetting(){
        return view('payout-user/settingdev');
    }

    function walletReport()
    {
        $user = Auth::user();
        $userId = $user->id;
        //$usertransaction = UserTransaction::where('userId',$userId)->orderBy('id','desc')->paginate(10);   
        $today = Carbon::today(); 
        return view('payout-user/walletreport',compact('today'));
    }

    public function walletReportData(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $start = $request['start'];
        $length = $request['length'];
        $date = $request['date'];
        $search_arr = $request['search'];
        $search = $search_arr['value'];
        $modelInstance = new UserTransaction();
        $getData = $modelInstance->getWalletDataAjax($start,$length,$date,$search,$userId);
        $totalRecords = UserTransaction::where("userId",$userId)->count();//$getData->count(); 
        $Data = $getData->get();
        $response = [
            'draw' => $request['draw'],
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $Data,
        ];
        return response()->json($response);
    }

    public function payoutReport()
    {
        return view('payout-user/payoutreport');
    }

    public function payoutReportData(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $start = $request['start'];
        $length = $request['length'];
        $date = $request['date'];
        $search_arr = $request['search'];
        $search = $search_arr['value'];
        $modelInstance = new PayoutModel();
        $getData = $modelInstance->getWalletDataAjax($start,$length,$date,$search,$userId);
        $totalRecords = PayoutModel::where("userId",$userId)->count();//$getData->count(); 
        $Data = $getData->get();

        $response = [
            'draw' => $request['draw'],
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $Data,
        ];
        return response()->json($response);
    }

    public function payinReport()
    {
        return view('payout-user/payinreport');
    }

    public function payinReportData(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $start = $request['start'];
        $length = $request['length'];
        $date = $request['date'];
        $search_arr = $request['search'];
        $search = $search_arr['value'];
        $modelInstance = new PayinModel();
        $getData = $modelInstance->getWalletDataAjax($start,$length,$date,$search,$userId);
        $totalRecords = PayinModel::where("userId",$userId)->count();//$getData->count(); 
        $Data = $getData->get();

        $response = [
            'draw' => $request['draw'],
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $Data,
        ];
        return response()->json($response);
    }

    public function walletTopupReport()
    {
        $today = Carbon::today();
        return view('payout-user/wallet-topup-request',compact('today'));
    }

    public function walletTopupReportData(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $start = $request['start'];
        $length = $request['length'];
        $date = $request['date'];
        $search_arr = $request['search'];
        $search = $search_arr['value'];
        $modelInstance = new WalletTopup();
        $getData = $modelInstance->getWalletDataAjax($start,$length,$date,$search,$userId);
        $totalRecords = $getData->count(); 
        $Data = $getData->get();

        $response = [
            'draw' => $request['draw'],
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $Data,
        ];
        return response()->json($response);
    }

    public function walletReportDataExport(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $date = $request['txndate'];
        $data = UserTransaction::whereDate('created_at', $date)->where('userId',$userId)->orderBy('id','desc')->get();

        $filename = 'wallet_txn_'.$date.'.csv';
        $file = fopen($filename,"w");

        // Header row - Remove this code if you don't want a header row in the export file.
        $header = array("Type","Date","OrderId", "Descrption", "Open Balance","Amount","Wallet balance","Status"); 
        fputcsv($file,$header); 

        foreach($data as $row){
            $type = $row->type;
            $created_at = $row->created_at;
            $orderId = $row->orderId;   
            $remark = $row->remark;
            $openBalance = $row->openBalance;
            $amount = $row->amount;
            $walletBalance = $row->walletBalance;   
            $status = $row->status;

            // Write to file 
            $users_arr = array($type, $created_at,$orderId, $remark, $openBalance, $amount,$walletBalance,$status);
            fputcsv($file,$users_arr); 
        }

        fclose($file);
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        readfile($filename);

        // deleting file
        unlink($filename);
        exit();        							
    }

    public function payoutReportDataExport(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $date = $request['txndate'];
        $data = PayoutModel::whereDate('created_at', $date)->where('userId',$userId)->orderBy('id','desc')->get();

        $filename = 'Payout_txn_'.$date.'.csv';
        $file = fopen($filename,"w");

        // Header row - Remove this code if you don't want a header row in the export file.
        $header = array("OrderId","Transaction Id","UTR","Name", "Account Number", "Ifsc","Amount","Charge","Gst","Net Amount","Status","Date"); 				
        fputcsv($file,$header); 

        foreach($data as $row){
            $OrderId = $row->orderId;
            $txnid = $row->txnId;
            $utr = $row->utr;   
            $beneName = $row->beneName;
            $beneAccount = $row->beneAccount;
            $beneIfsc = $row->beneIfsc;
            $amount = $row->amount;   
            $charge = $row->charge;
            $gst = $row->gst;   
            $totalAmount = $row->totalAmount;
            $status = $row->status;   
            $created_at = $row->created_at;

            // Write to file 
            $users_arr = array($OrderId, $txnid,$utr, $beneName, $beneAccount, $beneIfsc,$amount,$charge,$gst,$totalAmount,$status,$created_at);
            fputcsv($file,$users_arr); 
        }

        fclose($file);
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        readfile($filename);

        // deleting file
        unlink($filename);
        exit();     
    }

    public function payinReportDataExport(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $date = $request['txndate'];
        $data = PayinModel::whereDate('created_at', $date)->where('userId',$userId)->orderBy('id','desc')->get();

        $filename = 'Payin_txn_'.$date.'.csv';
        $file = fopen($filename,"w");

        // Header row - Remove this code if you don't want a header row in the export file.
        $header = array("OrderId","Transaction Id","UTR","Amount","Charge","Gst","Net Amount","Status","Date"); 				
        fputcsv($file,$header); 

        foreach($data as $row){
            $OrderId = $row->orderId;
            $txnid = $row->txnId;
            $utr = $row->utr;               
            $amount = $row->amount;   
            $charge = $row->charge;
            $gst = $row->gst;   
            $totalAmount = $row->totalAmount;
            $status = $row->status;   
            $created_at = $row->created_at;

            // Write to file 
            $users_arr = array($OrderId, $txnid,$utr,$amount,$charge,$gst,$totalAmount,$status,$created_at);
            fputcsv($file,$users_arr); 
        }

        fclose($file);
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        readfile($filename);

        // deleting file
        unlink($filename);
        exit();     
    }

    public function topupReportDataExport(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $date = $request['txndate'];
        $data = WalletTopup::whereDate('created_at', $date)->where('userId',$userId)->orderBy('id','desc')->get();

        $filename = 'wallet_topup_txn_'.$date.'.csv';
        $file = fopen($filename,"w");

        // Header row - Remove this code if you don't want a header row in the export file.
        $header = array("Date","Amount","Charge","Gst","Net Amount", "Utr","Status"); 									
        fputcsv($file,$header); 

        foreach($data as $row){
            $created_at = $row->created_at;
            $amount = $row->amount;   
            $charge = $row->charge;
            $gst = $row->gst;   
            $totalAmount = $row->totalAmount;
            $utr = $row->utr;   
            $status = "SUCCESS";

            // Write to file 
            $users_arr = array($created_at,$amount,$charge,$gst,$totalAmount,$utr,$status);
            fputcsv($file,$users_arr); 
        }

        fclose($file);
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        readfile($filename);

        // deleting file
        unlink($filename);
        exit();     
    }

    public function apiDocs()
    {
        return view('payout-user/apidocs');
    }

    ////************** Fund Request Module 2nd Feb 24 **************
    
    public function paymentRequestList()
    {
        $data = [];
        $user = Auth::user();
        $data['payrequest'] = PaymentRequest::where('userId',$user->id)->get();
        return view('payout-user/payrequestlist',$data);
    }

    public function addPayRequest()
    {
        return view('payout-user/addpayrequest');
        //return view('user/addbank1');
    }
    
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $this->UploadFile($request->file('file'), 'Products');//use the method in the trait
            Files::create([
                'path' => $path
            ]);
            return redirect()->route('files.index')->with('success', 'File Uploaded Successfully');
        }
    }

    public function storePayRequest(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'remarks' => 'required',
            'payment_type' => 'required',
            'from_bank' => 'required',
            'to_bank' => 'required',
            'reference_number' => 'required|unique:payment_requests,reference_number|unique:payout_transactions,orderId',
            'amount' => 'required',
        ]);

        if($validator->fails()) {
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => $validator->errors(),
            ];
            return redirect()->route('payout-user.add-payrequest')->with('error', $validator->errors());
        }
        $user = Auth::user();
        $requestArr = $request->all();
        $payment_req_data = new PaymentRequest([
            'userId' => $user->id,
            'amount' => $requestArr['amount'],
            'reference_number' => $requestArr['reference_number'],
            'from_bank' => $requestArr['from_bank'],
            'to_bank' => $requestArr['to_bank'],
            'payment_type' => $requestArr['payment_type'],
            'remarks' => $requestArr['remarks'],
            'is_approved' => 0,
            'approve_reject_remarks' => '',
            'created_at' => Carbon::now(),      
            'updated_at' => NULL, 
        ]);
        if($payment_req_data->save()) {
            return redirect()->route('payout-user.add-payrequest')->with('success', 'Payment Request added successfully.');
        } else {
            return redirect()->route('payout-user.add-payrequest')->with('error', 'Unable to add Payment Request!');
        }


    }

    public function editBank1(Bank $bank)
    {
        $data = [];
        $data['bank'] = $bank;
        return view('payout-user.editbank1',$data);
    }

    public function updateBank1(Request $request, Bank $bank)
    {
        $user = Auth::user();
        $this->validate($request, [
            'cus_name' => 'required',
            'acc_number' => 'required',
            'mobile_no' => 'required',
            'ifsc_code' => 'required',
            'pincode' => 'required|regex:/^\d{6}$/',
            'bank_name' => 'required',
            'payment_type' => 'required'
        ]);

        $requestArr = $request->all();

        $bank->user_id = $user->id;
        $bank->cus_name = strtoupper($requestArr['cus_name']);
        $bank->acc_number = $requestArr['acc_number'];
        $bank->mobile_no = $requestArr['mobile_no'];
        $bank->ifsc_code = strtoupper($requestArr['ifsc_code']);
        $bank->pincode = $requestArr['pincode'];
        $bank->bank_name = $requestArr['bank_name'];
        $bank->payment_type = $requestArr['payment_type'];
        $bank->updated_at = Carbon::now(); 

        if($bank->save()) {
            return redirect()->route('payout-user.editBank1',$bank->id)->with('success', 'Bank has been updated successfully.');
        } else {
            return redirect()->route('payout-user.editBank1',$bank->id)->with('error', 'Unable to update bank!');
        }
    }

    public function deleteBank1(Bank $bank)
    {
        $bank->delete();
        return redirect()->route('payout-user/bankList1')->with('success', 'Bank has been deleted successfully.');
    }
    
    
    
    ////************** Fund Request Module 2nd Feb 24 END **************

    
    
}
