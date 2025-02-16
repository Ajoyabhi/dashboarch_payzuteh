<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class ChangeApiRouteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $jsonData = $request->json()->all();        
        $Authorization = $request->header('Authorization');
        $getUser = User::where("user_token",$Authorization)->where("status",1)->where("api_status",1)->where("user_type",1)->first();
        if(empty($getUser)){
            $responseData = [                
                'status' => FALSE,
                'error'=> TRUE,
                'message' => 'Invalid credentials',
            ];
            return response()->json($responseData , 401);
        }

        $vouch = $getUser->vouch;
        $iserveu = $getUser->iserveu;
        if($vouch == 1){
            return redirect()->route('v2.doPayout');
        }
        
        if($iserveu == 1){
            return redirect()->route('v1/doPayout');
        }

        return $next($request);
    }
}
