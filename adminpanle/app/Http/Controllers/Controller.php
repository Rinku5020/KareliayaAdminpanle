<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
class Controller
{

    public function showLogin(){
        return view('auth.login');
    }
    

    public function loginValidateUser(Request $request){
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
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
    
}
  




    

