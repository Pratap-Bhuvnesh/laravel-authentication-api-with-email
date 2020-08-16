<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Mail\WelcomeMail;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function register(Request $request){ 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email|unique:users', 
            'password' => 'required|confirmed|min:6'
        ]);
      
        if ($validator->fails()) {
            $success['message'] = $validator->errors();
            $success['status'] = 'fail';
            return response()->json([$success], 401);
        }
        $user = User::create([
            'name' => $request->name, 
            'email' => $request->email, 
            'password' => bcrypt($request->password)
        ]);
        $user->sendApiEmailVerificationNotification();
        $success['message'] = "register successfull.Please Verfiy the email. Mail has been sent to inserted mail.";
        $success['result'] = $user;
        return response()->json(['success' => $success], 400);
       
    }
    public function login(Request $request){   
        $request->validate([
            'email' => 'required|email|exists:users,email', 
            'password' => 'required'
        ]);
       /*
        if( Auth::attempt(['email'=>$request->email, 'password'=>$request->password]) ) {  
            $user = Auth::user();
            $token = $user->createToken($user->email.'-'.now());
            
            return response()->json([
                'token' => $token->accessToken
            ]);
        }*/
        if( Auth::attempt(['email'=>$request->email, 'password'=>$request->password]) ) {  
            $user = Auth::user();
            if($user->email_verified_at !== NULL){
                $token = $user->createToken($user->email.'-'.now());
                $success['message'] = "Login successfull";
                $success['token'] = $token->accessToken;
                return response()->json(['success' => $success], 400);
            }else{
                return response()->json(['error'=>'Please Verify Email'], 401);
            }
        }else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
        
    }
}
