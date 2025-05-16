<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;


class Controller
{

  
    public function showLogin(){
        return view('auth.login');
    }
    

    public function loginValidateUser(Request $request){
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        
        $user = User::where('email', $request->input('email'))->first();

        

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return back()->with('error', 'Invalid credentials'); 
        }

        return redirect()->route('dashboard')->with('success', 'Login successful');
    }


    public function userRegister(Request $request){
        return view('auth.register');
    }


    public function registerValidateUser(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'number' => 'required|string|max:10',
            'password' => 'required|string|min:8|regex:/[a-zA-Z]/|regex:/[0-9]/',
        ]);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'number' => $request->input('number'),
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->route('showLogin')->with('success', 'Registration successful');
    }

    public function EmailVerify(Request $request)
    {
       return view('auth.emailverify');
    }
    
    public function Verification(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            
        ]);
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            return back()->with('error', 'Email not found');
        }
        $email = $request->input('email');
        $otp = rand(100000, 999999);
    
        session(['otp' => $otp, 'otp_email' => $email]);
        Mail::to($email)->send(new OtpMail($otp));

        
       return redirect()->route('otp')->with('success', 'OTP sent successfully');
    }


    public function otp(Request $request)
    {
        return view('auth.sendOTP');
    }
    public function sendVerification(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);
    
        $inputOtp = $request->input('otp');
        $sessionOtp = session('otp');
        $sessionEmail = session('otp_email'); 
        
    
        if ($inputOtp != $sessionOtp) {
            return redirect()->back()->with('error', 'Invalid OTP');
        }
    
        $user = User::where('email', $sessionEmail)->first();
    
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

    
        return redirect()->route('resetpassword')->with('success', 'OTP verified successfully');
    }

    public function resetpassword(Request $request)
    {
        return view('auth.resetpass');
    }

    public function resetPasswordUpdate(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|regex:/[a-zA-Z]/|regex:/[0-9]/',
            'confirm_password' => 'required|same:password',
        ]);
    
        $user = User::where('email', session('otp_email'))->first();
    
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
    
        $user->password = Hash::make($request->input('password'));
        $user->save();
    
        return redirect()->route('showLogin')->with('success', 'Password reset successfully');
    }

    

}
  




    

