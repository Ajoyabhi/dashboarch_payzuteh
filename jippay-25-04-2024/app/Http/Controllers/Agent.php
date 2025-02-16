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

class Agent extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        if (!Auth::check()) return 'NO';
    }

    public function index()
    {   
        $Authuser = Auth::user();
        $queryUser = User::where('user_type',1)->where('agent_id',$Authuser->id);
        $totalUsers = $queryUser->count();
        $getUserId = $queryUser->pluck('id')->toArray();
        $usertransaction = UserTransaction::with('user')->whereIn('userId', $getUserId)->orderBy('id','desc')->limit(5)->get();  
        $usersWithBalances = User::where('user_type',1)->whereIn('id', $getUserId)->sum('wallet');
        $today = Carbon::today();
        $status = ['SUCCESS','PENDING','PROCESSING'];
        $todayPayout = PayoutModel::whereDate('created_at', $today)->whereIn('userId', $getUserId)->whereIn('status',$status)->sum('amount');
        $totalPayout = PayoutModel::whereIn('status',$status)->whereIn('userId', $getUserId)->sum('amount');
        $todayTopUp = WalletTopup::whereDate('created_at', $today)->whereIn('userId', $getUserId)->where('status',"APPROVED")->sum('amount');
        $totalTopUp = WalletTopup::where('status',"APPROVED")->whereIn('userId', $getUserId)->sum('amount');
        return view('agent/dashboard',compact('usertransaction','totalUsers','usersWithBalances','todayPayout','totalPayout','todayTopUp','totalTopUp'));
    }

    public function usersData()
    {
        $Authuser = Auth::user();
        $queryUser = User::where('user_type',1)->where('agent_id',$Authuser->id);
        $getUserId = $queryUser->pluck('id')->toArray();
        $user = User::orderBy('id','desc')->whereIn('id', $getUserId)->whereNotIn('user_type',[0,2])->paginate(500);
        return view('agent/users',compact('user'));
    }

    function addAgentUser(Request $request)
    {
        if($_POST)
        {
            $request->validate([
                'name' => 'required',
                'user_name' => 'required|unique:users,user_name',
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
                'user_type' => 'required',
            ]);
            $user = Auth::user();
            $user_key = bin2hex(random_bytes(6));
            $user_token = bin2hex(random_bytes(20));
            User::create([
                'name' => $request['name'],
                'user_name' => $request['user_name'],
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
                'user_type' =>$request['user_type'],
                'agent_id'=>1,//$request['agent'],
                'created_by' => $user->id,   
                'rolling_reserve' =>0,         
                'payout_status' =>0,          
                'tecnical_issue' =>0,         
                'bank_deactive' =>0,         
                'iserveu' =>0,         
                'vouch' =>0,         
                'payout_callback' =>'www',         
                'payin_callback' =>'www',         
                'paydeer_token' =>'abc',         
                'wallet' =>0,         
            ]);
            
            return redirect()->route('add-agent')->with('success','Agent has been created successfully.');
        }
        //die("LLL");
        $agent = User::orderBy('id','desc')->whereNotIn('user_type',[0,1,2])->get();
        return view('agent/add-agent',compact('agent'));
    }

    function walletReport()
    {
        $Authuser = Auth::user();
        $queryUser = User::where('user_type',1)->where('agent_id',$Authuser->id);
        $getUserId = $queryUser->pluck('id')->toArray();    
        return view('agent/walletreport');
    }

    public function walletReportData(Request $request)
    {
        $Authuser = Auth::user();
        $queryUser = User::where('user_type',1)->where('agent_id',$Authuser->id);
        $getUserId = $queryUser->pluck('id')->toArray();  
        $start = $request['start'];
        $length = $request['length'];
        $date = $request['date'];
        $search_arr = $request['search'];
        $search = $search_arr['value'];
        $modelInstance = new UserTransaction();
        $getData = $modelInstance->getWalletDataAjax($start,$length,$date,$search,$getUserId);
        $totalRecords = UserTransaction::whereIn("userId",$getUserId)->count();//$getData->count(); 
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
        return view('agent/payoutreport');
    }

    public function payoutReportData(Request $request)
    {
        $Authuser = Auth::user();
        $queryUser = User::where('user_type',1)->where('agent_id',$Authuser->id);
        $getUserId = $queryUser->pluck('id')->toArray();  
        $start = $request['start'];
        $length = $request['length'];
        $date = $request['date'];
        $search_arr = $request['search'];
        $search = $search_arr['value'];
        $modelInstance = new PayoutModel();
        $getData = $modelInstance->getWalletDataAjax($start,$length,$date,$search,$getUserId);
        $totalRecords = PayoutModel::whereIn("userId",$getUserId)->count();//$getData->count(); 
        $Data = $getData->get();

        $response = [
            'draw' => $request['draw'],
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $Data,
        ];
        return response()->json($response);
    }

    public function WalletTopupReport()
    {
        $Authuser = Auth::user();
        $queryUser = User::where('user_type',1)->where('agent_id',$Authuser->id);
        $getUserId = $queryUser->pluck('id')->toArray();  
        $today = Carbon::today();
        $walletTopup = WalletTopup::whereDate('created_at', $today)->whereIn("userId",$getUserId)->where('status',"APPROVED")->orderBy('id','desc')->get();
        $todayTopUp = WalletTopup::whereDate('created_at', $today)->whereIn("userId",$getUserId)->where('status',"APPROVED")->sum('amount');
        $totalTopUp = WalletTopup::where('status',"APPROVED")->whereIn("userId",$getUserId)->sum('amount');
        return view('agent/wallet-topup-report',compact('walletTopup','totalTopUp','todayTopUp'));
    }

    public function topupReportDataExport(Request $request)
    {
        $Authuser = Auth::user();
        $queryUser = User::where('user_type',1)->where('agent_id',$Authuser->id);
        $getUserId = $queryUser->pluck('id')->toArray(); 
        $date = $request['txndate'];
        $submit = $request['submit'];
        if($submit == "EXPORT"){

            $selectedColumns = ['wallet_topups.amount','wallet_topups.charge','wallet_topups.gst','wallet_topups.totalAmount','wallet_topups.utr','wallet_topups.created_at','users.name'];
            $query = WalletTopup::whereDate('wallet_topups.created_at', $date)->select($selectedColumns)->join('users', 'wallet_topups.userId', '=', 'users.id');
            $data = $query->get();

            $filename = 'wallet_topup_txn_'.$date.'.csv';
            $file = fopen($filename,"w");

            // Header row - Remove this code if you don't want a header row in the export file.
            $header = array("Date","User","Amount","Charge","Gst","Net Amount", "Utr","Status"); 									
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
                $users_arr = array($created_at,$name,$amount,$charge,$gst,$totalAmount,$utr,$status);
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
            $walletTopup = WalletTopup::whereDate('created_at', $today)->whereIn("userId",$getUserId)->where('status',"APPROVED")->orderBy('id','desc')->get();
            $todayTopUp = WalletTopup::whereDate('created_at', $today)->whereIn("userId",$getUserId)->where('status',"APPROVED")->sum('amount');
            $totalTopUp = WalletTopup::where('status',"APPROVED")->whereIn("userId",$getUserId)->sum('amount');
            return view('agent/wallet-topup-report',compact('walletTopup','totalTopUp','todayTopUp'));
        }
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
        return view('admin/changepassword',compact('data'));
    }

}