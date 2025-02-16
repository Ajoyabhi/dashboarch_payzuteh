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
use App\Models\WalletTopup;
use App\Models\PlatformCharge;
use App\Models\PayoutModel;

class Staff extends Controller
{
    public function index()
    {
        $usertransaction = UserTransaction::with('user')->orderBy('id','desc')->limit(5)->get();  
        //$totalUsers = User::where('user_type',1)->count();
        $usersWithBalances = User::sum('wallet');
        $today = Carbon::today();
        $todayPayout = PayoutModel::whereDate('created_at', $today)->where('status',"SUCCESS")->sum('amount');
        $todayTopUp = WalletTopup::whereDate('created_at', $today)->where('status',"APPROVED")->sum('amount');
        return view('staff/dashboard',compact('usertransaction','usersWithBalances','todayPayout','todayTopUp'));
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
        return view('staff/changepassword',compact('data'));
    }

    function viewProfile()
    {
        return view('staff/view-profile');
    }

    function walletReport()
    {
        //$usertransaction = UserTransaction::with('user')->orderBy('id','desc')->paginate(10);    
        return view('staff/walletreport');
    }

    public function payoutReport()
    {
        return view('staff/payoutreport');
    }

    function WalletTopup(Request $request)
    {
        if($_POST){
            $request->validate([
                'user' => 'required',
                'amount' => ['required', 'numeric', 'min:1'],
                'remark' => 'required',
            ]); 

            $Authuser = Auth::user();

            $userid = $request['user'];
            $amount = $request['amount'];
            $remark = $request['remark'];

            WalletTopup::create([
                'userId' => $userid,
                'amount' => $amount,
                'requestedBy' => $Authuser->name,
                'requestedRemark' => $remark,  
                'status' => "PENDING",      
                'created_by' => $Authuser->id,
            ]);

            return redirect()->route('/staff/wallet-topup-request')->with('success','Wallet topup has been submited successfully.');
        }
        $users = User::orderBy('id','desc')->whereNotIn('user_type',[0,2])->get();
        return view('staff/wallet-topup',compact('users'));
    }

    function WalletTopupRequest(Request $request)
    {
        if($_POST){                 
            $Authuser = Auth::user();

            $status = $request['status'];
            $utr = $request['utr'];
            $requestId = $request['requestId'];
            $remark = $request['remark'];

            $getData = WalletTopup::findOrFail($requestId);

            if($status == "DENIED"){

                $data = [
                    'approvedBy'=>$Authuser->name,
                    'approvedRemark'=>$remark,
                    'status'=>$status,
                ];
                $getData->update($data);
                return redirect()->route('/staff/wallet-topup-request')->with('success','Wallet topup has been denied successfully.');
            }
            
            if($status == "APPROVED"){

                $checkutr = WalletTopup::where("utr",$utr)->get();
                
                if(count($checkutr) != 0){
                    return redirect()->route('/staff/wallet-topup-request')->with('success','this utr is already used please check.');
                }
                $userId = $getData->userId;
                $amount = $getData->amount;
                $openBal = $getData->wallet;
                $getCharge = PlatformCharge::where("userId",$userId)->first();

                $charge = $getCharge->charge;
                $gst = $getCharge->gst;
                if($charge == ""){
                    $charge = 2;
                }
                if($gst == ""){
                    $gst = 18;
                }                               
                $calCharge = $amount*$charge/100;
                $calGst = $calCharge*$gst/100;
                $totalCharge = $calCharge+$calGst;
                $totalAddAmount = $amount-$totalCharge;
                $data = [
                    'charge'=>$calCharge,
                    'gst'=>$calGst,
                    'totalAmount'=>$totalAddAmount,
                    'utr'=>$utr,
                    'approvedBy'=>$Authuser->name,
                    'approvedRemark'=>$remark,
                    'status'=>$status,
                ];
                $getData->update($data);

                $ipAddress = $request->ip();
                $txn_id = rand(12312,99099).Carbon::now()->timestamp;

                $user = User::findOrFail($userId);
                $openBal = $user->wallet;

                $closeBal = $openBal + $totalAddAmount;
                $UserInstance = new User();
                $UserInstance->addFund($userId,$totalAddAmount);
                $remark = "WALLET TOP UP BY AMOUNT ".$amount;
                UserTransaction::create([
                    'userId' => $userId,
                    'txnId' => $txn_id,
                    'orderId' => $txn_id,
                    'type' => "CREDIT",
                    'operator' => "WALLETTOPUP", 
                    'openBalance' =>$openBal,
                    'amount' => $totalAddAmount,
                    'walletBalance' =>$closeBal, 
                    'credit' =>$totalAddAmount, 
                    'debit' =>0,   
                    'remark' => $remark,   
                    'status'=>"SUCCESS", 
                    'requestIp' => $ipAddress,          
                    'created_by' => $Authuser->id,
                ]); 
                return redirect()->route('/staff/wallet-topup-request')->with('success','Wallet topup has been approved successfully.');
            }
        }        
        $today = Carbon::today();
        $WalletTopup = new WalletTopup();        
        $walletTopup = $WalletTopup->getWalletData();   
        $users = User::orderBy('id','desc')->whereNotIn('user_type',[0,2])->get();
        return view('staff/wallet-topup-request',compact('walletTopup','users'));
    }

    public function WalletTopupSearch(Request $request)
    {
        if($_POST){
            $user = $request['user'];
            $status = $request['status'];
            $date = $request['date'];

            $WalletTopup = new WalletTopup();        
            $walletTopup = $WalletTopup->getWalletDataSearch($user,$status,$date); 
            $users = User::orderBy('id','desc')->whereNotIn('user_type',[0,2])->get();
            return view('staff/wallet-topup-request',compact('walletTopup','users'));
        }
    }

    public function WalletTopupReport()
    {
        $today = Carbon::today();
        $walletTopup = WalletTopup::whereDate('created_at', $today)->where('status',"APPROVED")->orderBy('id','desc')->get();
        $todayTopUp = WalletTopup::whereDate('created_at', $today)->where('status',"APPROVED")->sum('totalAmount');
        $totalTopUp = WalletTopup::where('status',"APPROVED")->sum('totalAmount');
        return view('staff/wallet-topup-report',compact('walletTopup','totalTopUp','todayTopUp'));
    }

    public function topupReportDataExport(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $date = $request['txndate'];
        $submit = $request['submit'];
        if($submit == "EXPORT"){

            $selectedColumns = ['wallet_topups.amount','wallet_topups.utr','wallet_topups.created_at','users.name'];
            $query = WalletTopup::whereDate('wallet_topups.created_at', $date)->select($selectedColumns)->join('users', 'wallet_topups.userId', '=', 'users.id');
            $data = $query->get();

            $filename = 'wallet_topup_txn_'.$date.'.csv';
            $file = fopen($filename,"w");

            // Header row - Remove this code if you don't want a header row in the export file.
            $header = array("Date","User","Amount", "Utr","Status"); 									
            fputcsv($file,$header); 

            foreach($data as $row){
                $created_at = $row->created_at;
                $name = $row->name;
                $amount = $row->amount;   
                $charge = $row->charge;
                $gst = $row->gst;   
                $totalAmount = $row->totalAmount;
                $utr = $row->utr;   
                $status = "SUCCESS";

                // Write to file 
                $users_arr = array($created_at,$name,$amount,$utr,$status);
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

        if($submit == "VIEW")
        {
            $today = Carbon::today();
            $walletTopup = WalletTopup::whereDate('created_at', $date)->where('status',"APPROVED")->orderBy('id','desc')->get();
            $todayTopUp = WalletTopup::whereDate('created_at', $date)->where('status',"APPROVED")->sum('amount');
            $totalTopUp = WalletTopup::where('status',"APPROVED")->sum('amount');
            return view('staff/wallet-topup-report',compact('walletTopup','totalTopUp','todayTopUp'));
        }
    }

    function addUsers(Request $request)
    {
        if($_POST)
        {
            $request->validate([
                'name' => 'required',
                'mobile' => 'required|unique:users,mobile|regex:/^[6-9]\d{9}$/',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'pancard' => 'required|unique:users,pancard|regex:/^([A-Z]){5}([0-9]){4}([A-Z]){1}$/',
                'aadhaar' => 'required|unique:users,aadhaar_card|regex:/^\d{12}$/',
                'company' => 'required',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'pincode' => 'required',
            ]);
            $user = Auth::user();
            $user_key = bin2hex(random_bytes(6));
            $user_token = bin2hex(random_bytes(20));
            User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'mobile' => $request['mobile'],
                'password' => Hash::make($request['password']),
                'aadhaar_card' => $request['aadhaar'],
                'pancard' => $request['pancard'],
                'address' => $request['address'],
                'city' => $request['city'],
                'state' => $request['state'],
                'pincode' => $request['pincode'],
                'company_name'=>$request['company'],
                'user_key'=>$user_key,
                'user_token'=>$user_token,
                'status' => 1,
                'api_status' => 1,
                'user_type' =>1,
                'agent_id'=>1,//$request['agent'],
                'created_by' => $user->id,            
            ]);
            
            return redirect()->route('/staff/add-user')->with('success','User has been created successfully.');
        }
        $agent = User::orderBy('id','desc')->whereNotIn('user_type',[0,1,2])->get();
        return view('staff/add-user',compact('agent'));
    }

}
