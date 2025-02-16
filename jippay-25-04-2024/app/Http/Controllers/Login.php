<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\UserIp;

class Login extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Front/login');
    }

    public function verifyUserAuth(Request $request)  {
        
        $credentials = $request->validate([
            //'mobile' => 'required|regex:/[0-9]{10}/|digits:10',
            'user_name' => 'required',
            'password' => 'required',
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if($user->user_type == 0){
                return redirect()->intended('admin/dashboard');
            }
            if($user->user_type == 1){
                return redirect()->intended('user/dashboard');
            }
            if($user->user_type == 3){
                return redirect()->intended('agent/dashboard');
            }
            if($user->user_type == 2){
                $ipAddress = $request->ip();
                $checkIp = UserIp::where(['userId'=>$user->id,'ipAddress'=>$ipAddress])->first();
                if(empty($checkIp)){
                    auth()->logout();
                    return back()->withErrors([
                        'user_name' => 'Your Ip is not whitelisted '.$ipAddress,
                    ])->onlyInput('user_name');
                }
                return redirect()->intended('staff/dashboard');
            }
            
        }
 
        return back()->withErrors([
            'mobile' => 'The provided credentials do not match our records.',
        ])->onlyInput('mobile');
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/login');
    }

    public function showPayment()
    {
        return view('Front/showPaymentPage');
    }
}
