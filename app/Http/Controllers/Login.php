<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\UserIp;
use Illuminate\Support\Facades\Log;

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
        Log::info('verifyUserAuth function called');
        $credentials = $request->validate([
            //'mobile' => 'required|regex:/[0-9]{10}/|digits:10',
            'user_name' => 'required',
            'password' => 'required',
        ]);
        
        if (Auth::attempt($credentials)) {
            // $request->session()->regenerate();
            $user = Auth::user();
            if($user->user_type == 0){
                Log::info('verifyUserAuth function admin called');
                return redirect()->intended('admin/dashboard');
            }
            elseif($user->user_type == 1){
                Log::info('verifyUserAuth function 1 called');
                return redirect()->intended('user/dashboard');
            }
            elseif($user->user_type == 4){
                Log::info('verifyUserAuth function 4 called');
                return redirect()->intended('payout-user/dashboard');
            }
            elseif($user->user_type == 3){
                Log::info('verifyUserAuth function 3 called');
                return redirect()->intended('agent/dashboard');
            }
            elseif($user->user_type == 2){
                $ipAddress = $request->ip();
                $checkIp = UserIp::where(['userId'=>$user->id,'ipAddress'=>$ipAddress])->first();
                if(empty($checkIp)){
                    auth()->logout();
                    return back()->withErrors([
                        'user_name' => 'Your Ip is not whitelisted '.$ipAddress,
                    ])->onlyInput('user_name');
                }
                return redirect()->intended('staff/dashboard');
            } else {
                return redirect()->intended('/login');
            }
            
        }
        Log::info('verifyUserAuth function called end');
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
