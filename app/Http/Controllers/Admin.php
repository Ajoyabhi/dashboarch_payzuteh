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
use App\Models\ApiLog;
use App\Models\UserTransaction;
use App\Models\WalletTopup;
use App\Models\PlatformCharge;
use App\Models\PayoutModel;
use App\Models\PayinModel;
use App\Models\PayoutList;
use App\Models\PaymentRequest;
use App\Models\SettlementHistory;

class Admin extends Controller
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
    
    public function callGetMethod($url, $token='')
    {
        $url = 'http://localhost:3000/'.$url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $headers = array('Content-Type:application/json',"Accept:application/json","Authorization:".$token);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    
         $response = curl_exec ($ch);
         $err = curl_error($ch);  //if you need
         curl_close ($ch);
         return $response;
    }

    public function callPostMethod($url, $token, $body)
    {
        $url = 'http://localhost:3000/'.$url;
        // $url = 'https://ffb0-2401-4900-1c5a-e365-719f-2fd-7c89-20d.ngrok-free.app/'.$url;
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
        
        $url  = 'admin/reports/brief';
        $dataReturn = $this->callGetMethod($url);
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
        $totalUsers = $decodeDataReturn['data']['userCount'];
        $usersWithBalances = $decodeDataReturn['data']['usersWithBalances'];
        
        //Payout
        $todayPayout = $decodeDataReturn['data']['todayPayout'];
        $totalPayout = $decodeDataReturn['data']['totalPayout'];
        
        //Topups
        $todayTopUp = $decodeDataReturn['data']['todayTopup'];
        $totalTopUp = $decodeDataReturn['data']['totalTopup'];
        
        $todayTopPayinUsersResult = $decodeDataReturn['data']['todayTopPayinUsersResult'];
        $totalTopPayinUsersResult = $decodeDataReturn['data']['totalTopPayinUsersResult'];
        $todayTopPayoutUsersResult = $decodeDataReturn['data']['todayTopPayoutUsersResult'];
        $totalTopPayoutUsersResult = $decodeDataReturn['data']['totalTopPayoutUsersResult'];
        return view('admin/dashboard',compact(
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
            'totalProfit',
            'todayTopPayinUsersResult',
            'totalTopPayinUsersResult',
            'todayTopPayoutUsersResult',
            'totalTopPayoutUsersResult'
            )
        );
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

    function viewProfile()
    {
        return view('admin/view-profile');
    }

    function manageUsers()
    {
        $user = User::orderBy('id','desc')->whereNotIn('user_type',[0,2])->paginate(500);
        return view('admin/manage-user',compact('user'));
    }

    function addUsers(Request $request)
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
                'gst_no' =>isset($request['gst_no']) ? $request['gst_no'] : 'XXX',
                'business_type' =>isset($request['business_type']) ? $request['business_type'] : 'XXX',
                'pin' => 'XXX',
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
                'settlement' =>0,      
            ]);
            
            return redirect()->route('add-user')->with('success','User has been created successfully.');
        }
        $agent = User::orderBy('id','desc')->whereNotIn('user_type',[0,1,2])->get();
        return view('admin/add-user',compact('agent'));
    }

    function manageSettlement()
    {
        $user = User::orderBy('id','desc')->whereNotIn('user_type',[0,2])->paginate(500);
        return view('admin/manage-settlement',compact('user'));
    }
    
    function viewSettlement($id)
    {
        $user = SettlementHistory::orderBy('id','desc')->where('user_id',$id)->paginate(500);
        // echo "<pre>";print_r($user);exit;
        $totalSettlement = SettlementHistory::where('user_id', $id)->sum('amount');
        return view('admin/view-settlement',compact('user','totalSettlement'));
    }

    public function editUser (Request $request, User $user)
    {
        $data = [];
        $data['user'] = $user;
        return view('admin.edit-user',$data);
    }

    public function addSettlement (Request $request, User $user)
    {
        $data = [];
        $data['user'] = $user;
        return view('admin.add-settlement',$data);
    }

    function updateSettlement(Request $request, $id)
    {
        if($_POST) {
            $request->validate([
                'requestedamount' => 'required',//|digits_between:2,5
                'pin' => 'required'//|regex:/^\d{12}$/'
            ]);
            $admin = Auth::user();
            $user = User::find($id);
            $checkUser = User::where("pin",$_POST['pin'])->where("id",$admin->id)->get()->toArray();
            if(!empty($checkUser)){
                if(($user['wallet']) >= $_POST['requestedamount']){
                    $update_data = [
                        'settlement' => $_POST['previousamount']+$_POST['requestedamount'],
                        'wallet' => $user->wallet - $_POST['requestedamount'], 
                        'updated_by' => $user->id,            
                    ];
                    $user->update($update_data);
                    SettlementHistory::create([
                        'opening_settlement'=>$_POST['previousamount'],
                        'amount' => $_POST['requestedamount'],
                        'closing_settlement'=>$_POST['previousamount']+$_POST['requestedamount'],
                        'user_id' => $user->id,
                        'created_by' => $user->id,            
                    ]);
            
                    return redirect()->route('manage-settlement',$id)->with('success','Settlement has been updated successfully.');
                } else {
                    $msg = "User have only ". ($user->wallet-$user->lien)." usable balance!";
                    return redirect()->route('add-settlement',$id)->with('error',$msg);
                }
            } else {
                return redirect()->route('add-settlement',$id)->with('error','Pin is not correct!');
            }
        }
    }

    function updateUser(Request $request, $id)
    {
        if($_POST)
        {
            $request->validate([
                'name' => 'required',
                'mobile' => 'required|regex:/^[6-9]\d{9}$/',
                'email' => 'required|email',
                'password' => 'required',
                'pancard' => 'required|regex:/^([A-Z]){5}([0-9]){4}([A-Z]){1}$/',
                'aadhaar' => 'required|regex:/^\d{12}$/',
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

            $data = User::find($id);
            
            $data->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'mobile' => $request['mobile'],
                'password' => $request['password'],
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
                'agent_id'=>1,
                'updated_by' => $user->id,            
            ]);
            return redirect()->route('edit-user',$id)->with('success','User has been updated successfully.');
        }
        // $agent = User::orderBy('id','desc')->whereNotIn('user_type',[0,1,2])->get();
        // return view('admin/add-user',compact('agent'));
    }

    function manageStaff()
    {
        $user = User::orderBy('id','desc')->whereNotIn('user_type',[0,1])->paginate(1000);
        return view('admin/manage-staff',compact('user'));
    }

    function manageAgent()
    {
        $user = User::orderBy('id','desc')->where('user_type',3)->paginate(1000);
        return view('admin/manage-agent',compact('user'));
    }    

    function addStaff(Request $request)
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
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'pincode' => 'required',
            ]);
            $user = Auth::user();
            $user_key = "";
            $user_token = "";
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
                'company_name'=> "",
                'user_key'=>$user_key,
                'user_token'=>$user_token,
                'status' => 1,
                'api_status' => 0,
                'user_type' =>2,
                'created_by' => $user->id,            
            ]);
    
            return redirect()->route('add-staff')->with('success','Staff has been created successfully.');
        }
        return view('admin/add-staff');
    }

    public function editStaff(Request $request, User $user)
    {
        $data = [];
        $data['user'] = $user;
        return view('admin.edit-staff',$data);
    }

    function updateStaff(Request $request,$id)
    {
        if($_POST)
        {
            $request->validate([
                'name' => 'required',
                'mobile' => 'required|regex:/^[6-9]\d{9}$/',
                'email' => 'required|email',
                'password' => 'required',
                'pancard' => 'required|regex:/^([A-Z]){5}([0-9]){4}([A-Z]){1}$/',
                'aadhaar' => 'required|regex:/^\d{12}$/',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'pincode' => 'required',
            ]);
            $user = Auth::user();
            $user_key = "";
            $user_token = "";

            $data = User::find($id);
            $data->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'mobile' => $request['mobile'],
                'password' => $request['password'],
                'aadhaar_card' => $request['aadhaar'],
                'pancard' => $request['pancard'],
                'address' => $request['address'],
                'city' => $request['city'],
                'state' => $request['state'],
                'pincode' => $request['pincode'],
                'company_name'=>"",
                'user_key'=>$user_key,
                'user_token'=>$user_token,
                'status' => 1,
                'api_status' => 0,
                'user_type' =>2,
                'updated_by' => $user->id,            
            ]);
    
            return redirect()->route('edit-staff',$id)->with('success','Staff has been updated successfully.');
        }
        return view('admin/add-staff');
    }

    function addAgent(Request $request)
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
            $user_key = "";
            $user_token = "";
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
                'api_status' => 0,
                'user_type' =>3,
                'created_by' => $user->id,            
            ]);
    
            return redirect()->route('add-agent')->with('success','Agent has been created successfully.');
        }
        return view('admin/add-agent');
    }
    
    function manageUsersPayStatus(Request $request)
    {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $type = $_POST['type'];
        $pay = $_POST['pay'];

        // if($status == "DEACTIVE"){
        //     $statusnew = 0;
        // }else{
        //     $statusnew = 1; 
        // }
        $user = User::findOrFail($id);
        if($pay == "payin"){
            $data = ['payin_status'=>$status];
        } else if($pay == "payout"){
            $data = ['payouts_status'=>$status];
        } else{
            echo "No data has been updated.";
        die;
        }
        $user->update($data);
        echo "Status has been updated successfully";
        die;
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

    function resetUserPassword()
    {
        $id = $_POST['id'];        
        $user = User::findOrFail($id);    
        $password = "12345678";   
        $user->update([
            'password' => Hash::make($password)
        ]);
        echo "password has been updated successfully";
        die;
    }

    function manageUserCharge($id,Request $request)
    {
        $userId = $id;
        $admin = Auth::user();
        $userCharge = UserCharge::where('userId',$userId)->get();
        $userIps = UserIp::where('userId',$userId)->get();
        $PlatformCharge = PlatformCharge::where('userId',$userId)->get();
        return view('admin/manage-user-charge',compact('userId','userCharge','userIps','PlatformCharge'));
    }
    
    function userDashboard($id,Request $request)
    {
        $userId = $id;
        $user = User::findOrFail($id);
        $admin = Auth::user();
        $token = $admin->user_token;
        // echo $user->user_token;exit;
        
        $url  = 'user/reports/brief';
        $dataReturn = $this->callGetMethod($url, $user->user_token, json_encode(array("user_id"=>$id)));
        $decodeDataReturn = json_decode($dataReturn, TRUE);
        // echo '<pre>';print_r($decodeDataReturn);exit;
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
        $user_ips = UserIp::where('userId',$userId)->where('created_by', $admin->id)->get();
        $userCharge = UserCharge::where('userId',$userId)->where('created_by',$admin->id)->get();

        //Dashboard Changes 13 Mar 24 END
        $data = ['labels' => ['Payin', 'Payout'],'todayData' => [$todayPayout, $todayPayin],'totalData' => [$totalPayin, $totalPayout]];
        return view('admin/user-dashboard',compact('data','todayPayout','totalPayout','todayPayin','totalPayin','todayProfit','totalProfit','user','user_ips','userCharge'));
    }

    // function deleteUserCharge(Request $request)
    // {
    //     $resp = [];
    //     $id = $request->id;
    //     $UserCharge = UserCharge::find($id);
    //     if($UserCharge->delete()) {
    //         $resp['type'] = "success";
    //         $resp['msg'] = "User Charge has been deleted successfully";
    //     } else {
    //         $resp['type'] = "error";
    //         $resp['msg'] = "Unable to delete User Charge.";
    //     }
    //     return response()->json($resp);
    // }

    // function deleteUserIp(Request $request)
    // {
    //     $resp = [];
    //     $id = $request->id;
    //     $UserIp = UserIp::find($id);
    //     if($UserIp->delete()) {
    //         $resp['type'] = "success";
    //         $resp['msg'] = "User IP has been deleted successfully";
    //     } else {
    //         $resp['type'] = "error";
    //         $resp['msg'] = "Unable to delete User IP.";
    //     }
    //     return response()->json($resp);
    // }

    function saveUserCharge(Request $request)
    {
        $user = Auth::user();
        UserCharge::create([
            'userId' => $request['userid'],
            'start_amount' => $request['start_amount'],
            'end_amount' => $request['end_amount'],
            'payout_charge' => $request['payout_charge'],
            'payin_charge' => $request['payin_charge'],
            'agent_payin_charge' => 0,
            'agent_payout_charge' => 0,
            'payin_total_charge' => $request['payin_charge'] + 0,
            'payout_total_charge' =>$request['payout_charge'] + 0,
            'payin_charge_type' => $request['payin_charge_type'],
            'payout_charge_type' => $request['payout_charge_type'],
            'created_by' => $user->id,
        ]);

        // //return redirect()->route('admin/manage-user-charge/'.$request['userid'])->with('success','User charge has been added successfully.');
        return redirect()->back()->with('success', 'User charge has been added successfully');
    }
    
    function updateUserCharge(Request $request,$id)
    {
        $user = Auth::user();
        
        $user_charge = UserCharge::where('id',$id)->where('userId',$request['userid'])->first();
        if($user_charge) {
            $agent_payin_charge = $user_charge->agent_payin_charge;
            $agent_payout_charge = $user_charge->agent_payout_charge;

            $user_charge->start_amount = $request['start_amount'];
            $user_charge->end_amount = $request['end_amount'];
            $user_charge->payin_charge = $request['payin_charge'];
            $user_charge->payout_charge = $request['payout_charge'];
            $user_charge->agent_payin_charge = $agent_payin_charge;
            $user_charge->agent_payout_charge = $agent_payout_charge;
            $user_charge->payin_total_charge = $request['payin_charge'] + $agent_payin_charge;
            $user_charge->payout_total_charge = $request['payout_charge'] + $agent_payout_charge;
            $user_charge->payin_charge_type = $request['payin_charge_type'];
            $user_charge->payout_charge_type = $request['payout_charge_type'];
            $user_charge->updated_by = $user->id;
            $user_charge->update();
            return redirect()->back()->with('success','User charge has been updated successfully');
        }
    }
    
    function deleteUserCharge($id)
    {
        $UserCharge = UserCharge::find($id);
        $UserCharge->delete();
        return redirect()->back()->with('success','User charge has been deleted successfully');
    }


    function saveUserIP(Request $request)
    {
        $user = Auth::user();
        UserIp::create([
            'userId' => $request['userid'],
            'ipAddress' => $request['ipaddress'],            
            'created_by' => $user->id,
        ]);        
        return redirect()->back()->with('ip-success', 'User Ip address has been added successfully');
    }
    
    function updateUserIP(Request $request,$id)
    {
        $user = Auth::user();
        $user_ip = UserIp::where('id',$id)->where('userId',$request['userid'])->first();
        $user_ip->ipAddress = $request['ipaddress'];
        $user_ip->updated_by = $user->id;
        $user_ip->updated_at = Carbon::now();
        if($user_ip->save()){
            return redirect()->back()->with('ip-success', 'User Ip address has been updated successfully');
        } else {
            return redirect()->back()->with('ip-error', 'Unable to update User Ip');
        }
    }
    
    function deleteUserIP($id)
    {
        $user_ip = UserIp::find($id);
        $user_ip->delete();
        return redirect()->back()->with('ip-success','User Ip address has been deleted successfully');
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
        return redirect()->back()->with('platform-success', 'User platform charge has been added successfully');
    }
    
    function updateUserPlatformCharge(Request $request,$id)
    {
        $user = Auth::user();
        $platform_charge = PlatformCharge::where('id',$id)->where('userId',$request['userid'])->first();
        $platform_charge->charge = $request['platform_charge'];
        $platform_charge->gst = $request['gst'];
        $platform_charge->updated_by = $user->id;
        $platform_charge->updated_at = Carbon::now();
        if($platform_charge->save()) {
            return redirect()->back()->with('platform-success', 'User platform charge has been updated successfully');
        } else {
            return redirect()->back()->with('platform-error', 'Unable to update User platform charge');
        }
    }
    
    function deleteUserPlatformCharge($id)
    {
        $platform_charge = PlatformCharge::find($id);
        $platform_charge->delete();
        return redirect()->back()->with('platform-success','User platform charge has been deleted successfully');
    }

    function saveUserSetting(Request $request)
    {
        $id = $request['userid'];
        $lien = $request['lien'];
        $rolling_reserve = $request['rolling_reserve'];
        $payin_callback = $request['payin_callback'];
        $payout_callback = $request['payout_callback'];
        $user = User::findOrFail($id);
        $data = [
            'lien'=>$lien,
            'rolling_reserve'=>$rolling_reserve,
            'payin_callback'=>$payin_callback,
            'payout_callback'=>$payout_callback,
        ];
        $user->update($data);
        return redirect()->route('manage-users')->with('success','User setting has been updated successfully.');
    }

    function updateUserFund(Request $request)
    {
        $url = 'admin/user-fund-update';
        $token = '';
        
        $data = $this->callPostMethod($url, $token, json_encode($request));
        $response = json_decode($data,true);
        if($response['error']){
            return redirect()->route('manage-users')->with('success','Insufficient user fund.');
        }
        return redirect()->route('manage-users')->with('success','User fund has been updated successfully.');
        $userid = $request['userid'];
        $fund_type = $request['fund_type'];
        $amount = $request['amount'];
        $remark = $request['remark'];
        $ipAddress = $request->ip();
        $txn_id = rand(12312,99099).Carbon::now()->timestamp;
        $User = new User();
        $user = User::findOrFail($userid);
        $openBal = $user->wallet;

        if($fund_type == "CREDIT"){
            $closeBal = $openBal + $amount;
            // $user->wallet += $amount;
            // $user->save();
            $query = $User->addFund($userid,$amount);
            //$query = $User->addFund(1,$amount);
        }
        if($fund_type == "DEBIT"){
            if($openBal < $amount){
                return redirect()->route('manage-users')->with('success','Insufficient user fund.');
            }                
            $closeBal = $openBal - $amount;
           
            $query = $User->deductFund($userid,$amount);
            //$query = $User->deductFund(1,$amount);
        }

        // $user->update([
        //     'wallet' => $closeBal,
        // ]);
        
        if($fund_type == "DEBIT"){
            UserTransaction::create([
                'userId' => $userid,
                'txnId' => $txn_id,
                'orderId' => $txn_id,
                'type' => $fund_type,
                'operator' => $fund_type, 
                'openBalance' =>$openBal, 
                'amount' => $amount,
                'walletBalance' =>$closeBal, 
                'credit' =>0, 
                'debit' =>$amount,   
                'remark' => $remark,  
                'status'=>"SUCCESS", 
                'requestIp' => $ipAddress,          
                'created_by' => $user->id,
            ]); 
        }

        if($fund_type == "CREDIT"){
            UserTransaction::create([
                'userId' => $userid,
                'txnId' => $txn_id,
                'orderId' => $txn_id,
                'type' => $fund_type,
                'operator' => $fund_type, 
                'openBalance' =>$openBal, 
                'amount' => $amount,
                'walletBalance' =>$closeBal, 
                'credit' =>$amount, 
                'debit' =>0,   
                'remark' => $remark,  
                'status'=>"SUCCESS",  
                'requestIp' => $ipAddress,          
                'created_by' => $user->id,
            ]); 
        }
        return redirect()->route('manage-users')->with('success','User fund has been updated successfully.');
    }

    function walletReport()
    {
        //$usertransaction = UserTransaction::with('user')->orderBy('id','desc')->paginate(10);    
        return view('admin/walletreport');
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

            return redirect()->route('/admin/wallet-topup-request')->with('success','Wallet topup has been submited successfully.');
        }
        $users = User::orderBy('id','desc')->whereNotIn('user_type',[0,2])->get();
        return view('admin/wallet-topup',compact('users'));
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
                return redirect()->route('/admin/wallet-topup-request')->with('success','Wallet topup has been denied successfully.');
            }
            
            if($status == "APPROVED"){

                $checkutr = WalletTopup::where("utr",$utr)->get();
                
                if(count($checkutr) != 0){
                    return redirect()->route('/admin/wallet-topup-request')->with('success','this utr is already used please check.');
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
                return redirect()->route('/admin/wallet-topup-request')->with('success','Wallet topup has been approved successfully.');
            }
        }        
        $today = Carbon::today();
        $WalletTopup = new WalletTopup();        
        $walletTopup = $WalletTopup->getWalletData();       
        $users = User::orderBy('id','desc')->whereNotIn('user_type',[0,2])->get();
        return view('admin/wallet-topup-request',compact('walletTopup','users'));
    }

    public function walletReportData(Request $request)
    {
        $url = 'admin/report/wallet';
        $token = '';
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
    }

    public function chargebackReport()
    {
        return view('admin/chargebackreport');
    }

    public function chargebackReportData(Request $request)
    {
        $start = $request['start'];
        $length = $request['length'];
        $date = $request['date'];
        $search_arr = $request['search'];
        $search = $search_arr['value'];
        $modelInstance = new UserTransaction();
        $getData = $modelInstance->getChargebackDataAjax($start,$length,$date,$search);
        $totalRecords = UserTransaction::where("type","CHARGEBACK")->count();//$getData->count(); 
        $Data = $getData->get();
        $response = [
            'draw' => $request['draw'],
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $Data,
        ];
        return response()->json($response);
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
            return view('admin/wallet-topup-request',compact('walletTopup','users'));
        }
    }

    public function payoutReport()
    {
        return view('admin/payoutreport');
    }

    public function payoutReportData(Request $request)
    {
        $url = 'admin/report/payout';
        $token = '';
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
    }

    public function payinReport()
    {
        return view('admin/payinreport');
    }

    public function payinReportData(Request $request)
    {
        $url = 'admin/report/payin';
        $token = '';
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
    }

    

    public function readCSV(Request $request)
    {

        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        // Get the uploaded CSV file
        $csvFile = $request->file('csv_file');

        $data = [];

        // Open the CSV file for reading
        if (($handle = fopen($csvFile->path(), 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                if($row[0] != "transfer_mode"){
                    if($row[0] != ""){                    
                    $data[] = $row;                    
                    }
                }                
            }
            fclose($handle);
        }
        $dataSend = [];
        foreach($data as $row){
            $dataSend['transferMode'] = $row[0];
            $dataSend['referenceNumber'] = rand(12312,99099).Carbon::now()->timestamp;
            $dataSend['transferAmount'] = $row[2];
            $dataSend['user_name'] = $row[3];
            $dataSend['mobileNumber'] = $row[4];
            $dataSend['name'] = $row[5];
            $dataSend['accountNumber'] = $row[6];
            $dataSend['bankIfsc'] = $row[7];
            $dataSend['beneBankName'] = $row[8];    
            
            $send = json_encode($dataSend);
            $this->hitpayout($send);       
        }
    }

    function hitpayout($data){

        $header = array(
            'Content-Type:application/json',
            'Authorization: f1ebf003789c44677ad68cd4debaaa5d2d8dc2a9'
        );
    
        $curl = curl_init();
    
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://dashboard.seospay.in/api/v2/doPayout",
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $header
        ]);
    
        $response = curl_exec($curl);
        //echo $data;
        // Check for cURL errors
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            echo "cURL Error: " . $error;
        }
    
        // Close cURL session
        curl_close($curl);
    
        // Return the response
        echo $response;
    }



    public function bulkPayout()
    {
        return view('admin/bulkpayout');
    }

    public function WalletTopupReport()
    {
        $today = Carbon::today();
        $walletTopup = WalletTopup::whereDate('created_at', $today)->where('status',"APPROVED")->orderBy('id','desc')->get();
        $todayTopUp = WalletTopup::whereDate('created_at', $today)->where('status',"APPROVED")->sum('amount');
        $totalTopUp = WalletTopup::where('status',"APPROVED")->sum('amount');
        return view('admin/wallet-topup-report',compact('walletTopup','totalTopUp','todayTopUp'));
    }

    public function datasavepre(Request $request)
    {

        $csvFile = $request->file('csv_file');
        $data = [];

        if (($handle = fopen($csvFile->path(), 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                if($row[0] != "transfer_mode"){
                    if($row[0] != ""){                    
                    $data[] = $row;                    
                    }
                }                
            }
            fclose($handle);
        }
        $dataSend = [];
        foreach($data as $row){
            $dataSend['id'] = $row[0];
            $dataSend['userId'] = $row[1];
            $dataSend['txnId'] = $row[2];
            $dataSend['orderId'] = $row[3];
            $dataSend['type'] = $row[4];
            $dataSend['operator'] = $row[5];
            $dataSend['openBalance'] = $row[6];
            $dataSend['amount'] = $row[7];
            $dataSend['walletBalance'] = $row[8];    
            $dataSend['credit'] = $row[9];  
            $dataSend['debit'] = $row[10];  
            $dataSend['remark'] = $row[11];  
            $dataSend['requestIp'] = $row[12];  
            $dataSend['api'] = $row[13];  
            $dataSend['refundId'] = $row[14];  
            $dataSend['status'] = $row[15];  
            $dataSend['created_by'] = $row[16];  
            $dataSend['updated_by'] = 1;  
            $dataSend['created_at'] = $row[18];  
            $dataSend['updated_at'] = $row[19];
            
            //$send = json_encode($dataSend);
            //$this->hitpayout($send);
            //print_r($dataSend);die;
            UserTransaction::create($dataSend);
            
        }
    }


    public function walletReportDataExport(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $date = $_POST['txndate']; 
        $url = 'admin/report/wallet';
        $token = '';
        $postData = [
            "start"=>0,
            "length"=>100000,
            "date"=>$_POST['txndate'],
            "search_value"=>""
        ];
        $data = $this->callPostMethod($url, $token, json_encode($postData));
        $res = json_decode($data,true);
        echo "<pre>";print_r($res);exit;

        $selectedColumns = ['user_transactions.orderId','user_transactions.type','user_transactions.openBalance','user_transactions.amount','user_transactions.walletBalance','user_transactions.remark','user_transactions.created_at','user_transactions.status','users.name'];
        $query = UserTransaction::whereDate('user_transactions.created_at', $date)->select($selectedColumns)->join('users', 'user_transactions.userId', '=', 'users.id');
        $data = $query->get();

        $filename = 'wallet_txn_'.$date.'.csv';
        $file = fopen($filename,"w");

        // Header row - Remove this code if you don't want a header row in the export file.
        $header = array("Type","Date","OrderId","User", "Descrption", "Open Balance","Amount","Wallet balance","Status"); 
        fputcsv($file,$header); 

        foreach($data as $row){
            $type = $row->type;
            $created_at = $row->created_at;
            $orderId = $row->orderId;
            $name = $row->name;   
            $remark = $row->remark;
            $openBalance = $row->openBalance;
            $amount = $row->amount;
            $walletBalance = $row->walletBalance;   
            $status = $row->status;

            // Write to file 
            $users_arr = array($type, $created_at,$orderId, $name,$remark, $openBalance, $amount,$walletBalance,$status);
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
        $url = 'admin/report/payout';
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
        $url = 'admin/report/payin';
        $user = Auth::user();
        $token = $user->user_token;
        $postData = [
            "start"=>$request['start'],
            "length"=>$request['length'],
            "date"=>$request['txndate'],
            "search_value"=>''
        ];
        // echo $request['txndate'];exit;
        $payinRes = $this->callPostMethod($url, $token, json_encode($postData));
        $data = json_decode($payinRes,true);
        // echo "<pre>";print_r($data);exit;
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

    public function topupReportDataExport(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
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
            $walletTopup = WalletTopup::whereDate('created_at', $date)->where('status',"APPROVED")->orderBy('id','desc')->get();
            $todayTopUp = WalletTopup::whereDate('created_at', $date)->where('status',"APPROVED")->sum('amount');
            $totalTopUp = WalletTopup::where('status',"APPROVED")->sum('amount');
            return view('admin/wallet-topup-report',compact('walletTopup','totalTopUp','todayTopUp'));
        }
    }

    public function getDailyData()
    {
        $PayoutModel = new PayoutModel();
        $data = $PayoutModel->getDailyTxn();
        echo "<PRE>";
        print_r($data);
    }

    public function getPayoutData()
    {
        $today = "2023-10-02 00:00:00";

        $PayoutData = PayoutModel::whereDate('created_at', $today)->where('status',"PENDING")->limit(100)->get();
        foreach($PayoutData as $data)
        {
            $sendData['referenceNumber'] = $data->orderId;
            $send = json_encode($sendData);
            $this->hitCheckStatus($send);
        }
    }

    function hitCheckStatus($data){

        $header = array(
            'Content-Type:application/json',
            'Authorization: c584ead917cf964b6fa6145aeff7c0d2f3f98ce1'
        );
    
        $curl = curl_init();
    
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://dashboard.seospay.in/api/v1/checkstatus",
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $header
        ]);
    
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            echo "cURL Error: " . $error;
        }
        // Close cURL session
        curl_close($curl);
        // Return the response
        echo $response;
    }

    function manageStaffIp($id,Request $request)
    {
        $userid = $id;
        $userIps = UserIp::where('userId',$userid)->get();
        return view('admin/manage-staff-ip',compact('userid','userIps'));
    }

   public function addStaffIp(Request $request)
   {
        $user = Auth::user();
        UserIp::create([
            'userId' => $request['userid'],
            'ipAddress' => $request['ipaddress'],            
            'created_by' => $user->id,
        ]);        
        return redirect()->back()->with('success', 'User Ip address has been added successfully');
   }

    public function deductChargeback(Request $request)
    {
        if($_POST)
        {
            $utr = $request['utr'];
            $getTxn = PayinModel::where('utr',$utr)->get()->toArray();
            if(count($getTxn) == 0)
            {
                return redirect()->back()->with('success', 'Utr not found');
            }

            $userId = (int)$getTxn[0]['userId'];
            $orderId = $getTxn[0]['orderId'];
            $txnId = $getTxn[0]['txnId'];
            $contactId = $getTxn[0]['contactId'];
            $amount = $getTxn[0]['amount'];
            $charge = $getTxn[0]['charge'];
            $utr = $getTxn[0]['utr'];
            $totalAmount = $getTxn[0]['totalAmount'];
            $totalAmount = $getTxn[0]['totalAmount'];

            $getUser = User::where("id",$userId)->first();

            $openBal = $getUser->wallet;
            $closeBal = $openBal - $totalAmount;  

            $UserInstance = new User();
            $UserInstance->deductFund($userId,$totalAmount);

            $remark = "Deduct ChargeBack";
            UserTransaction::create([
                'userId' => $userId,
                'txnId' => $txnId,
                'orderId' => $orderId,
                'type' => "DEBIT",  
                'operator' => "CHARGEBACK",
                'openBalance' =>$openBal, 
                'amount' => $totalAmount,
                'walletBalance' =>$closeBal,
                'credit' =>0, 
                'debit' =>$totalAmount,
                "status" => "SUCCESS",
                'remark' => $remark,   
                'api'=>"PINWALLET",
                'requestIp' => "SYSTEM",          
                'created_by' => 1,
            ]);

            return redirect()->back()->with('success', 'ChargeBack Deducted Successfully');

        }
        return view('admin/deductChargeback');
    }

    /////
    public function payoutList()
    {
        $data = [];
        $data['payout_lists'] = PayoutList::all();
        return view('admin/payoutlist',$data);
    }

    public function payoutStatus(Request $request, PayoutList $payoutlist)
    {
        $resp = [];
        $admin_code = "1234567890";
        $txn_id = "NEST".rand(123121,990999).Carbon::now()->timestamp;
        $request_arr = $request->all();
        try {
            if($request_arr['status'] == 2) {
                $payoutlist->status = 2;
                $payoutlist->updated_at = Carbon::now();
                if($payoutlist->save()) {
                    return redirect()->route('admin.payoutList')->with('success', 'Payout Request has been rejected successfully.');
                } else {
                    return redirect()->route('admin.payoutList')->with('error', 'Unable to reject Payout Request!');
                }   
            } 
            if($request_arr['status'] == 1){
                if($admin_code == $request_arr['admin_code']) {
                    $user = User::where('id',$payoutlist->user_id)->first();
                    $user_token = $user->user_token;
                    // echo "<pre>";
                    // print_r($user_token);
                    // echo "</pre>";
                    // exit();
                    // $request = [
                    //     "name"=> $payoutlist->cus_name,
                    //     "accountNumber"=> $payoutlist->accountNumber,
                    //     "bankIfsc"=> $payoutlist->bankIfsc,
                    //     "mobileNumber"=>$payoutlist->mobileNumber,
                    //     "beneBankName"=> $payoutlist->beneBankName,
                    //     "referenceNumber"=> $payoutlist->ref_number,
                    //     "transferAmount"=> $payoutlist->amount,
                    //     "transferMode"=> $payoutlist->transferMode
                    // ]; 
                    $request = [
                        "beneficiary_account_number" => $payoutlist->accountNumber,
                        "beneficiary_bank_ifsc" => $payoutlist->bankIfsc,
                        "beneficiary_bank_name" => $payoutlist->beneBankName,
                        "beneficiary_name" => $payoutlist->cus_name,
                        "payment_mode" => $payoutlist->transferMode,
                        "amount" => $payoutlist->amount,
                        "reference" => $payoutlist->ref_number,
                    ]; 

                    $payload = json_encode($request);
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        // CURLOPT_URL => 'https://api.paydexsolutions.in/api/v2/doPayout',
                        // CURLOPT_URL => 'https://api.nestpay.in/api/v6/doPayout',
                        CURLOPT_URL => 'https://dashboard.jippay.com/api/v6/doPayout',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>$payload,
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Authorization: '.$user_token,
                        ),
                    ));
        
                    $response = curl_exec($curl);
                    curl_close($curl); 

                    ApiLog::create([
                        'txnId' => $txn_id,
                        'request' => $payload,
                        'response' => $response,  
                        'service' => "PAYOUT",
                        'service_api' =>"HAODA",   
                    ]);

                    $output = json_decode($response,true);
                    // echo "<pre>";
                    // print_r($output);
                    // echo "</pre>";
                    // exit();
                    
                    if($output['status'] == TRUE) {
                        $payoutlist->status = 1;
                        $payoutlist->updated_at = Carbon::now();

                        if($payoutlist->save()) {
                            return redirect()->route('admin.payoutList')->with('success', 'Payout Request has been approved successfully.');
                        } else {
                            return redirect()->route('admin.payoutList')->with('error', 'Unable to approve Payout Request!');
                        }

                    } else {
                        return redirect()->route('admin.payoutList')->with('error', $output['message']);
                    }

                } else {
                    return redirect()->route('admin.payoutList')->with('error', 'Admin Code is worng!');
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.payoutList')->with('error', 'Oops something went wrong!');
        }
    }

    /********** Fund Request Module -> Admin appproval 4th Feb 24 --- start */

    public function paymentRequestList()
    {
        $data = [];
        $user = Auth::user();
        //$data['payrequest'] = PaymentRequest::where('is_active',1)->get();

        $selectedColumns = ['payment_requests.id','payment_requests.userId','payment_requests.amount','payment_requests.reference_number','payment_requests.from_bank','payment_requests.to_bank','payment_requests.payment_type','payment_requests.remarks','payment_requests.pay_proof_img','payment_requests.is_approved','payment_requests.approve_reject_remarks','payment_requests.is_active','payment_requests.status','users.name', 'users.wallet'];
        $query = PaymentRequest::where('payment_requests.is_active', 1)->select($selectedColumns)->join('users', 'payment_requests.userId', '=', 'users.id');
        $data['payrequest'] = $query->get();

        
        return view('admin/payrequestlist',$data);
    }

    public function addPayRequest()
    {
        return view('admin/payrequest-list');
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

    function payReqApprRej(Request $request)
    {
        $requestArr = $request->all();
        $appRejArr = ['APPROVE' => 1, 'REJECT' => 2 ];
        $user = Auth::user();
        $data = ['is_approved' => $appRejArr[$requestArr['approve_reject']], 'approve_reject_remarks' => $requestArr['remarks'], 'status' => $requestArr['approve_reject']];
        $PayRequsest = new PaymentRequest();
        $PayRequsest->updatePaymentRequest($data,$requestArr['payReqId'], $requestArr['payReqUserId'],$requestArr['payReqAmount'], $requestArr['approve_reject']);
        
        return redirect()->back()->with('success', 'Your request changed successfully!');
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
            return redirect()->route('user.add-payrequest')->with('error', $validator->errors());
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
            'created_at' => Carbon::now(),      
            'updated_at' => NULL, 
        ]);
        if($payment_req_data->save()) {
            return redirect()->route('user.add-payrequest')->with('success', 'Payment Request added successfully.');
        } else {
            return redirect()->route('user.add-payrequest')->with('error', 'Unable to add Payment Request!');
        }
    }

    /********** Fund Request Module -> Admin appproval 4th Feb 24 --- end */

}