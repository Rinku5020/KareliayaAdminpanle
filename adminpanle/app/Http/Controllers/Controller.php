<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;


class Controller
{


    public function showLogin()
    {
        return view('auth.login');
    }


    public function loginValidateUser(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        

        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

            // Retrieve user from the database
            $user = User::where('email', $request->input('email'))->first();

            // Check if user exists and password matches
            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                return back()->with('error', 'Invalid credentials');
            }

            // Store user info in session
            session([
                'loggedIn'  => true,
                'account_id'   => $user->id,
                'user_name' => $user->name,
            ]);

            // Redirect to dashboard (ensure 'dashboard' route exists in your web.php)
            return redirect()->route('dashboard')->with('success', 'Login successful');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred during login. Please try again.');
        }
    }


    public function userRegister(Request $request)
    {
        return view('auth.register');
    }



    public function registerValidateUser(Request $request)
    {
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
        // dd(session()->all());


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

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('showLogin')->with('status', 'You have been logged out!');
    }

    public function profile()
    {
        $userId = session('account_id');
        $userName = session('user_name');
        $user = User::where('id', $userId)->where('name', $userName)->first();

        return view('auth.profile', compact('user'));
    }


    public function updatePass(Request $request, $id)
{
    $request->validate([
        'new_password' => 'required|min:6|same:new_password_confirmation',
        'new_password_confirmation' => 'required'
    ], [
        'new_password.required' => 'Please enter new password',
        'new_password.min' => 'Password must be at least 6 characters',
        'new_password.same' => 'Passwords do not match',
        'new_password_confirmation.required' => 'Please confirm your password',
    ]);

    $user = User::findOrFail($id);
    $user->password = bcrypt($request->new_password);
    $user->save();

    return redirect()->back()->with('success', 'Password updated successfully.');
}

    

}
