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
use App\Models\Bank;
use App\Models\PayinModel;
use App\Models\PayoutList;

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

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    
    public function callGetMethod($url,$token)
    {
        $url = 'http://pay.jippay.com:3000/'.$url;
        $headers = array("Accept:application/json", "Content-Type:application/json", "Authorization:".$token);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    
         $response = curl_exec ($ch);
         $err = curl_error($ch);  //if you need
         curl_close ($ch);
         return $response;
    }

    public function callPostMethod($url, $token, $body)
    {
        $url = 'http://pay.jippay.com:3000/'.$url;
        $headers = array('Content-Type:application/json',"Accept:application/json","Authorization:".$token);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
    
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    
         $response = curl_exec ($ch);
         $err = curl_error($ch);  //if you need
         curl_close ($ch);
         return $response;
    }

    public function index()
    {   
        $url  = 'agent/reports/brief';
        $user = Auth::user();
        $token = $user->user_token;
        $dataReturn = $this->callGetMethod($url,$token);
        $decodeDataReturn = json_decode($dataReturn, TRUE);
        // echo "<pre>";print_r($decodeDataReturn);exit;
        $status = ['SUCCESS'];
        
        //Today Profit
        $todayPayinProfit = $decodeDataReturn['data']['todayPayinProfit'];
        $todayPayoutProfit = $decodeDataReturn['data']['todayPayoutProfit'];
        $todayProfit = round(($todayPayinProfit+$todayPayoutProfit), 2);
        
        //Total Profit
        $totalPayinProfit = $decodeDataReturn['data']['totalPayinProfit'];
        $totalPayoutProfit = $decodeDataReturn['data']['totalPayoutProfit'];
        $totalProfit = round(($totalPayinProfit+$totalPayoutProfit), 2);
        
        //Payin
        $todayPayin = $decodeDataReturn['data']['todayPayin'];
        $totalPayin = $decodeDataReturn['data']['totalPayin'];
        
        $usableBalnce = User::selectRaw('sum(lien + rolling_reserve) as usableBal')->where('user_type',1)->whereIn('status',$status)->first();
        $usableBalnce = $usableBalnce->usableBal;
        
        $usertransaction = $decodeDataReturn['data']['userTransactions'];  
        $totalUsers = $decodeDataReturn['data']['totalUsers'];
        $usersWithBalances = $decodeDataReturn['data']['usersWithBalances'];
        
        //Payout
        $todayPayout = $decodeDataReturn['data']['todayPayout'];
        $totalPayout = $decodeDataReturn['data']['totalPayout'];
        
        //Topups
        $todayTopUp = $decodeDataReturn['data']['todayTopup'];
        $totalTopUp = $decodeDataReturn['data']['totalTopup'];
        
        return view('agent/dashboard',compact(
            'usertransaction',
            'totalUsers',
            'usersWithBalances',
            'usableBalnce', 
            'todayPayin',
            'totalPayin',
            'todayPayout',
            'totalPayout',
            'todayPayinProfit',
            'todayPayoutProfit',
            'todayProfit',
            'totalPayinProfit',
            'totalPayoutProfit',
            'totalProfit'
            )
        );

        // return view('agent/dashboard',compact('usertransaction','totalUsers','usersWithBalances','todayPayin','totalPayin','todayPayout','totalPayout','todayTopUp','totalTopUp'));
    }

    public function usersData()
    {
        $Authuser = Auth::user();
        $queryUser = User::where('user_type',1)->where('agent_id',$Authuser->id);
        $getUserId = $queryUser->pluck('id')->toArray();
        $user = User::orderBy('id','desc')->whereIn('id', $getUserId)->whereNotIn('user_type',[0,2,3])->paginate(500);
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
                'gst_no' => 'required',
                'business_type' => 'required',
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
                'agent_id'=>$user->id,//$request['agent'],
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
                'gst_no' => $request['gst_no'],
                'business_type' => $request['business_type']  ,
                'settlement' =>0,    
                'pin' => 'XXX',  
            ]);
            
            return redirect()->route('add-agent-user')->with('success','User has been created successfully.');
        }
        //die("LLL");
        $agent = User::orderBy('id','desc')->whereNotIn('user_type',[0,1,2])->get();
        // $agent = User::orderBy('id','desc')->whereNotIn('user_type',[0,2,3])->get();
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
        $url = '/agent/report/wallet';
        $user = Auth::user();
        $token = $user->user_token;
        $postData = [
            "start"=>$request['start'],
            "length"=>$request['length'],
            "date"=>$request['date'],
            "search_value"=>$request['search']['value']
        ];
        $data = $this->callPostMethod($url, $token, json_encode($postData));
        $res = json_decode($data,true);
        $response = [
            'draw' => $request['draw'],
            'recordsTotal' => $res['data']['recordsTotal'],
            'recordsFiltered' => $res['data']['recordsFiltered'],
            'data' => $res['data']['data'],
        ];
        return response()->json($response);
        // $Authuser = Auth::user();
        // $queryUser = User::where('user_type',1)->where('agent_id',$Authuser->id);
        // $getUserId = $queryUser->pluck('id')->toArray();  
        // $start = $request['start'];
        // $length = $request['length'];
        // $date = $request['date'];
        // $search_arr = $request['search'];
        // $search = $search_arr['value'];
        // $modelInstance = new UserTransaction();
        // $getData = $modelInstance->getWalletDataAjax($start,$length,$date,$search,$getUserId);
        // $totalRecords = UserTransaction::whereIn("userId",$getUserId)->count();//$getData->count(); 
        // $Data = $getData->get();
        // $response = [
        //     'draw' => $request['draw'],
        //     'recordsTotal' => $totalRecords,
        //     'recordsFiltered' => $totalRecords,
        //     'data' => $Data,
        // ];
        // return response()->json($response);
    }
    
    public function payinReport()
    {
        return view('agent/payinreport');
    }

    public function payinReportData(Request $request)
    {
        $url = 'agent/report/payin';
        $user = Auth::user();
        $token = $user->user_token;
        $postData = [
            "start"=>$request['start'],
            "length"=>$request['length'],
            "date"=>$request['date'],
            "search_value"=>$request['search']['value']
        ];
        $data = $this->callPostMethod($url, $token, json_encode($postData));
        $res = json_decode($data,true);
        // echo "<pre>";print_r($res);exit;   
        $response = [
            'draw' => $request['draw'],
            'recordsTotal' => $res['data']['recordsTotal'],
            'recordsFiltered' => $res['data']['recordsFiltered'],
            'data' => $res['data']['data'],
        ];
        return response()->json($response);
        // $start = $request['start'];
        // $length = $request['length'];
        // $date = $request['date'];
        // $search_arr = $request['search'];
        // $search = $search_arr['value'];
        // $modelInstance = new PayinModel();
        // $getData = $modelInstance->getWalletDataAjax($start,$length,$date,$search);
        // $totalRecords = PayinModel::count();//$getData->count(); 
        // $Data = $getData->get();

        // $response = [
        //     'draw' => $request['draw'],
        //     'recordsTotal' => $totalRecords,
        //     'recordsFiltered' => $totalRecords,
        //     'data' => $Data,
        // ];
        // return response()->json($response);
    }

    public function payoutReport()
    {
        return view('agent/payoutreport');
    }

    public function payoutReportData(Request $request)
    {
        $url = 'agent/report/payout';
        $user = Auth::user();
        $token = $user->user_token;
        $postData = [
            "start"=>$request['start'],
            "length"=>$request['length'],
            "date"=>$request['date'],
            "search_value"=>$request['search']['value']
        ];
        $data = $this->callPostMethod($url, $token, json_encode($postData));
        $res = json_decode($data,true);
        $response = [
            'draw' => $request['draw'],
            'recordsTotal' => $res['data']['recordsTotal'],
            'recordsFiltered' => $res['data']['recordsFiltered'],
            'data' => $res['data']['data'],
        ];
        return response()->json($response);
        // $Authuser = Auth::user();
        // $queryUser = User::where('user_type',1)->where('agent_id',$Authuser->id);
        // $getUserId = $queryUser->pluck('id')->toArray();  
        // $start = $request['start'];
        // $length = $request['length'];
        // $date = $request['date'];
        // $search_arr = $request['search'];
        // $search = $search_arr['value'];
        // $modelInstance = new PayoutModel();
        // $getData = $modelInstance->getWalletDataAjax($start,$length,$date,$search,$getUserId);
        // $totalRecords = PayoutModel::whereIn("userId",$getUserId)->count();//$getData->count(); 
        // $Data = $getData->get();

        // $response = [
        //     'draw' => $request['draw'],
        //     'recordsTotal' => $totalRecords,
        //     'recordsFiltered' => $totalRecords,
        //     'data' => $Data,
        // ];
        // return response()->json($response);
    }
    
    public function payoutReportDataExport(Request $request)
    {
        $url = 'agent/report/payout';
        $user = Auth::user();
        $token = $user->user_token;
        $date = $request['txndate'];
        $postData = [
            "start"=>$request['start'],
            "length"=>$request['length'],
            "date"=>$request['txndate'],
            "search_value"=>''
        ];
        $payoutRes = $this->callPostMethod($url, $token, json_encode($postData));
        $data = json_decode($payoutRes,true);
        
        // $user = Auth::user();
        // $userId = $user->id;
        // $date = $request['txndate'];
        // $data = PayoutModel::whereDate('created_at', $date)->where('userId',$userId)->orderBy('id','desc')->get();

        // $selectedColumns = ['payout_transactions.orderId','payout_transactions.txnId','payout_transactions.amount','payout_transactions.charge','payout_transactions.gst','payout_transactions.totalAmount','payout_transactions.beneName','payout_transactions.beneAccount','payout_transactions.beneIfsc','payout_transactions.utr','payout_transactions.status','payout_transactions.created_at','users.name'];
        // $query = PayoutModel::whereDate('payout_transactions.created_at', $date)->select($selectedColumns)->join('users', 'payout_transactions.userId', '=', 'users.id');
        // $data = $query->get();

        $filename = 'Payout_txn_'.$date.'.csv';
        $file = fopen($filename,"w");

        // Header row - Remove this code if you don't want a header row in the export file.
        $header = array("OrderId","User","Transaction Id","UTR","Name", "Account Number", "Ifsc","Amount","Charge","Gst","Net Amount","Status","Date"); 				
        fputcsv($file,$header); 

        foreach($data['data']['data'] as $row){
            $OrderId = $row['orderId'];
            $name = $row['userName'];
            $txnid = $row['txnId'];
            $utr = isset($row['utr']) ? $row['utr'] : "-";   
            $beneName = isset($row['beneName']) ? $row['beneName'] : "-";
            $beneAccount = isset($row['beneAccount']) ? $row['beneAccount'] : "-";
            $beneIfsc = isset($row['beneIfsc']) ? $row['beneIfsc'] : "-";
            $amount = isset($row['amount']) ? $row['amount'] : "-";   
            $charge = isset($row['charge']) ? $row['charge'] : "-";
            $gst = isset($row['gst']) ? $row['gst'] : "-";   
            $totalAmount = isset($row['totalAmount']) ? $row['totalAmount'] : "-";
            $status = $row['status'];   
            $created_at = $row['created_at'];

            // Write to file 
            $users_arr = array($OrderId,$name,$txnid,$utr, $beneName, $beneAccount, $beneIfsc,$amount,$charge,$gst,$totalAmount,$status,$created_at);
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
        $url = 'agent/report/payin';
        $user = Auth::user();
        $token = $user->user_token;
        $postData = [
            "start"=>$request['start'],
            "length"=>$request['length'],
            "date"=>$request['txndate'],
            "search_value"=>''
        ];
        $payinRes = $this->callPostMethod($url, $token, json_encode($postData));
        $data = json_decode($payinRes,true);
        echo "<pre>";print_r($data);exit;
        // $user = Auth::user();
        // $userId = $user->id;
        // $date = $request['txndate'];
        // $data = PayinModel::whereDate('created_at', $date)->where('userId',$userId)->orderBy('id','desc')->get();

        // $selectedColumns = ['payin_transactions.orderId','payin_transactions.txnId','payin_transactions.amount','payin_transactions.charge','payin_transactions.gst','payin_transactions.totalAmount','payin_transactions.utr','payin_transactions.status','payin_transactions.created_at','users.name'];
        // $query = PayinModel::whereDate('payin_transactions.created_at', $date)->select($selectedColumns)->join('users', 'payin_transactions.userId', '=', 'users.id');
        // $data = $query->get();

        $filename = 'Payin_txn_'.$request['txndate'].'.csv';
        $file = fopen($filename,"w");

        // Header row - Remove this code if you don't want a header row in the export file.
        $header = array("OrderId","User","Transaction Id","UTR","Amount","Charge","Gst","Net Amount","Status","Date"); 				
        fputcsv($file,$header); 

        foreach($data['data']['data'] as $row){
            $OrderId = $row['orderId'];
            $name = $row['userName'];
            $txnid = $row['txnId'];
            $utr = isset($row['utr']) ? $row['utr'] : '';               
            $amount = $row['amount'];   
            $charge = isset($row['charge']) ? $row['charge'] : '';
            $gst = isset($row['gst']) ? $row['gst'] : '';   
            $totalAmount = isset($row['totalAmount']) ? $row['totalAmount'] : '';
            $status = $row['status'];   
            $created_at = $row['created_at'];

            // Write to file 
            $users_arr = array($OrderId,$name,$txnid,$utr,$amount,$charge,$gst,$totalAmount,$status,$created_at);
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
        return view('agent/changepassword',compact('data'));
    }

    function userDashboard($id,Request $request)
    {
        $userId = $id;
        $user = User::findOrFail($id);
        $agent = Auth::user();
        $token = $agent->user_token;

        $url  = 'user/reports/brief';
        $dataReturn = $this->callGetMethod($url, $user->user_token, json_encode(array("user_id"=>$id)));
        $decodeDataReturn = json_decode($dataReturn, TRUE);
        // echo "<pre>";print_r($decodeDataReturn);exit;
        $status = ['SUCCESS'];
        
        //Today Profit
        $todayPayinProfit = $decodeDataReturn['data']['todayPayinProfit'];
        $todayPayoutProfit = $decodeDataReturn['data']['todayPayoutProfit'];
        $todayProfit = round(($todayPayinProfit+$todayPayoutProfit), 2);
        
        //Total Profit
        $totalPayinProfit = $decodeDataReturn['data']['totalPayinProfit'];
        $totalPayoutProfit = $decodeDataReturn['data']['totalPayoutProfit'];
        $totalProfit = round(($totalPayinProfit+$totalPayoutProfit), 2);

        //Payin
        $todayPayin = $decodeDataReturn['data']['todayPayin'];
        $totalPayin = $decodeDataReturn['data']['totalPayin'];
        
        //User balence and transactions
        $usableBalnce = User::selectRaw('sum(lien + rolling_reserve) as usableBal')->where('user_type',1)->whereIn('status',$status)->first();
        $usableBalnce = $usableBalnce->usableBal;
        $usertransaction = $decodeDataReturn['data']['userTransactions']; 
        //print_r($usertransaction);exit;
        // $totalUsers = $decodeDataReturn['data']['userCount'];
        // $usersWithBalances = $decodeDataReturn['data']['usersWithBalances'];
        
        //Payout
        $todayPayout = $decodeDataReturn['data']['todayPayout'];
        $totalPayout = $decodeDataReturn['data']['totalPayout'];
        
        //Topups
        $todayTopUp = $decodeDataReturn['data']['todayTopup'];
        $totalTopUp = $decodeDataReturn['data']['totalTopup'];

        // User dashboard data - 29-04-2024
        $user_ips = UserIp::where('userId',$userId)->get();
        $userCharge = UserCharge::where('userId',$userId)->get();
        
        //Dashboard Changes 13 Mar 24 END
        $data = ['labels' => ['Payin', 'Payout'],'todayData' => [$todayPayout, $todayPayin],'totalData' => [$totalPayin, $totalPayout]];
        // echo "<pre>";print_r($data);exit;
        return view('agent/user-dashboard',compact('data','todayPayout','totalPayout','todayPayin','totalPayin','todayProfit','totalProfit','user','user_ips','userCharge'));
    }

    function manageUserCharge($id,Request $request)
    {
        $userId = $id;
        $agent = Auth::user();
        $userCharge = UserCharge::where('userId',$userId)->get();
        $userIps = UserIp::where('userId',$userId)->get();
        $PlatformCharge = PlatformCharge::where('userId',$userId)->get();
        return view('agent/manage-user-charge',compact('userId','userCharge','userIps','PlatformCharge'));
    }

    function manageUsersStatus(Request $request)
    {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $type = $_POST['type'];

        if($status == "DEACTIVE"){
            $statusnew = 0;
        }else{
            $statusnew = 1;
        }
        $user = User::findOrFail($id);
        if($type == "API"){
            $data = ['api_status'=>$statusnew];
        }else if($type == "status"){
            $data = ['status'=>$statusnew];
        }else if($type == "TECHNICAL"){
            $data = ['tecnical_issue'=>$statusnew];
        }else if($type == "BYBANK"){
            $data = ['bank_deactive'=>$statusnew];
        }else if($type == "vouch"){
            $data = ['vouch'=>$statusnew];
        }else if($type == "iserveu"){
            $data = ['iserveu'=>$statusnew];
        }else{
            echo "No data has been updated.";
        die;
        }
        
        $user->update($data);
        echo "Status has been updated successfully";
        die;
    }

    public function bankList()
    {
        $data = [];
        $user = Auth::user();
        $data['banks'] = Bank::where('user_id',$user->id)->get();
        return view('agent.banks',$data);
    }

    public function addBank()
    {
        return view('agent/add-bank');
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
            return redirect()->route('agent.addBank')->with('success', 'Bank has been added successfully.');
        } else {
            return redirect()->route('agent.addBank')->with('error', 'Unable to add bank!');
        }
    }

    public function editBank(Bank $bank)
    {
        $data = [];
        $data['bank'] = $bank;
        return view('agent.edit-bank',$data);
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
            return redirect()->route('agent.editBank',$bank->id)->with('success', 'Bank has been updated successfully.');
        } else {
            return redirect()->route('agent.editBank',$bank->id)->with('error', 'Unable to update bank!');
        }
    }

    public function deleteBank(Bank $bank)
    {
        $bank->delete();
        return redirect()->route('agent.bankList')->with('success', 'Bank has been deleted successfully.');
    }

    public function doPayout()
    {
        $data = [];
        $user = Auth::user();
        $data['user'] = $user;
        $data['banks'] = Bank::where('user_id',$user->id)->get();
        return view('agent/payout',$data);
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
                    return redirect()->route('agent.dopayout')->with('success', 'Thank you for your Payout Request. We will take action soon.');
                } else {
                    return redirect()->route('agent.dopayout')->with('error', 'Unable to take Payout Request! Please try again leter.');
                }
            } else {
                return redirect()->route('agent.dopayout')->with('error', 'Minimum withdraw balance should be 100 or more.');
            }
        } else {
            return redirect()->route('agent.dopayout')->with('error', 'You have not sufficient amount to payout.');
        }
    }


    public function comission()
    {
        $data = [];
        $user = Auth::user();
        $today = Carbon::today();
        $status = ['SUCCESS'];
        $total_payin_comission = 0;
        $total_payout_comission = 0;
        $total_payin_comission_data = 0;
        $total_payout_comission_data = 0;

        $today_payin_comission = 0;
        $today_payout_comission = 0;
        $today_payin_comission_data = 0;
        $today_payout_comission_data = 0;
        $available_balance = User::where('user_type',3)->where('id',$user->id)->sum('wallet');
        $data['available_balance'] = round(($available_balance), 2);
        $usable_balance = User::where('user_type',3)->where('id',$user->id)->first();
        $data['usable_balance'] = $usable_balance->settlement;
        $users = User::where('agent_id',$user->id)->get();

        //Total Profit
        // payin
        foreach ($users as $key => $user_val) {
            $payinTotal[] = PayinModel::selectRaw('sum(charge) as total')->where('userId',$user_val->id)->whereIn('status',$status)->first();
            foreach ($payinTotal as $key => $payinTotal_val) {
                if($payinTotal_val['total'] != "") {
                    $UserCharge = UserCharge::where('userId',$user_val->id)->first();
                    $total_payin_comission = $payinTotal_val['total'] - $UserCharge->payin_charge;
                } else {
                    $total_payin_comission = 0;
                }
            }
            $total_payin_comission_data += $total_payin_comission ;
        }

        // payout
        foreach ($users as $key => $user_val) {
            $payoutTotal[] = PayoutModel::selectRaw('sum(charge) as total')->where('userId',$user_val->id)->whereIn('status',$status)->first();
            
            foreach ($payoutTotal as $key => $payoutTotal_val) {
                if($payoutTotal_val['total'] != "") {
                    $UserCharge = UserCharge::where('userId',$user_val->id)->first();
                    $total_payout_comission = $UserCharge->payout_charge - $payoutTotal_val['total'];
                }
            }
            $total_payout_comission_data +=  $total_payout_comission;
        }
        $data['totalProfit'] = round(($total_payin_comission_data + $total_payout_comission_data), 2);

        //Today Profit
        // payin
        foreach ($users as $key => $user_val) {
            $todayPayinTotal[] = PayinModel::selectRaw('sum(charge) as total')->where('userId',$user_val->id)->whereDate('created_at', $today)->whereIn('status',$status)->first();
            foreach ($todayPayinTotal as $key => $todayPayinTotal_val) {
                if($todayPayinTotal_val['total'] != "") {
                    $UserCharge = UserCharge::where('userId',$user_val->id)->first();
                    $today_payin_comission = $todayPayinTotal_val['total'] - $UserCharge->payin_charge;
                } else {
                    $today_payin_comission = 0;
                }
            }
            $today_payin_comission_data += $today_payin_comission ;
        }

        // payout
        foreach ($users as $key => $user_val) {
            $todayPayoutTotal[] = PayoutModel::selectRaw('sum(charge) as total')->where('userId',$user_val->id)->whereDate('created_at', $today)->whereIn('status',$status)->first();
            
            foreach ($todayPayoutTotal as $key => $todayPayoutTotal_val) {
                if($todayPayoutTotal_val['total'] != "") {
                    $UserCharge = UserCharge::where('userId',$user_val->id)->first();
                    $today_payout_comission = $UserCharge->payout_charge - $todayPayoutTotal_val['total'];
                }
            }
            $today_payout_comission_data +=  $today_payout_comission;
        }
        $data['todayProfit'] = round(($today_payin_comission_data + $today_payout_comission_data), 2);
        $queryUser = User::where('user_type',1)->where('agent_id',$user->id);
        $getUserId = $queryUser->pluck('id')->toArray();  
        $usertransaction = UserTransaction::whereIn("userId",$getUserId)->orderBy('id','desc')->get();//$getData->count(); 
        // $usertransaction = UserTransaction::with('user')->orderBy('id','desc')->limit(5)->get();  
        $data['usertransaction'] = $usertransaction;

        return view('agent/comission',$data);
    }

    function saveUserSetting(Request $request)
    {
        $id = $request['userid'];
        $payin_callback = $request['payin_callback'];
        $payout_callback = $request['payout_callback'];
        $user = User::findOrFail($id);
        $data = [
            'payin_callback'=>$payin_callback,
            'payout_callback'=>$payout_callback,
        ];
        $user->update($data);
        return redirect()->route('agent/users')->with('success','User setting has been updated successfully.');
    }

    function saveUserCharge(Request $request)
    {
        $user = Auth::user();

        $user_charge = UserCharge::where('userId',$request['userid'])->where('start_amount',$request['start_amount'])->where('end_amount',$request['end_amount'])->first();
        if($user_charge) {
            $payout_charge = $user_charge->payout_charge;
            $payin_charge = $user_charge->payin_charge;
            $user_charge->start_amount = $request['start_amount'];
            $user_charge->end_amount = $request['end_amount'];
            $user_charge->payout_charge = $payout_charge;
            $user_charge->payin_charge = $payin_charge;
            $user_charge->agent_payin_charge = $request['agent_payin_charge'];
            $user_charge->agent_payout_charge = $request['agent_payout_charge'];
            $user_charge->payin_total_charge = $request['agent_payin_charge'] + $payin_charge;
            $user_charge->payout_total_charge = $request['agent_payout_charge'] + $payout_charge;
            $user_charge->payin_charge_type = $request['payin_charge_type'];
            $user_charge->payout_charge_type = $request['payout_charge_type'];
            $user_charge->updated_by = $user->id;
            $user_charge->update();
            return redirect()->back()->with('success','User charge has been updated successfully');
        } else {
            UserCharge::create([
                'userId' => $request['userid'],
                'start_amount' => $request['start_amount'],
                'end_amount' => $request['end_amount'],
                'agent_payin_charge' => $request['agent_payin_charge'],
                'agent_payout_charge' => $request['agent_payout_charge'],
                'payin_total_charge' => $request['agent_payin_charge'],
                'payout_total_charge' => $request['agent_payout_charge'],
                'payin_charge_type' => $request['payin_charge_type'],
                'payout_charge_type' => $request['payout_charge_type'],
                'created_by' => $user->id,
            ]);
            return redirect()->back()->with('success', 'User charge has been added successfully');
        }

        //return redirect()->route('admin/manage-user-charge/'.$request['userid'])->with('success','User charge has been added successfully.');
    }

    function saveUserPlatformCharge(Request $request)
    {
        $user = Auth::user();
        PlatformCharge::create([
            'userId' => $request['userid'],
            'charge' => $request['platform_charge'], 
            'gst' => $request['gst'],            
            'created_by' => $user->id,
        ]);        
        return redirect()->back()->with('success', 'User platform charge has been added successfully');
    }

    function saveUserIP(Request $request)
    {
        $user = Auth::user();
        UserIp::create([
            'userId' => $request['userid'],
            'ipAddress' => $request['ipaddress'],            
            'created_by' => $user->id,
        ]);        
        return redirect()->back()->with('success', 'User Ip address has been added successfully');
    }

}