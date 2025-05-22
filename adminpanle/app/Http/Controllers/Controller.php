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

            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);


            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->input('email'))->first();

            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                return back()->with('error', 'Invalid credentials');
            }
            if ($user->status == 0) {
               
                return back()->withErrors(['email' => 'Your account is pending approval by admin.']);
            }
            if ($user->status == 2) {
                return back()->withErrors(['email' => 'Your account has been declined by admin.']);
            }
            // Store user info in session
            session([
                'loggedIn'  => true,
                'account_id'   => $user->id,
                'user_name' => $user->name,
                'role' => $user->role,
            ]);

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
        return redirect()->route('showLogin')->with('success', 'Send Registration request successfully');
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


    public function showApproval()
    {
        if (session('role') !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $pendingUsers = User::where('status', 0)->get();
        return view('auth.approve', compact('pendingUsers'));
    }

    public function approveUser($id)
    {
        if (session('role') !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $user = User::findOrFail($id);
        $user->status = 1;
        $user->save();

        return redirect()->back()->with('success', 'User approved successfully');
    }
    public function declineUser(User $user, $id)
    {
        if (session('role') !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $user = User::findOrFail($id);
        $user->status = 2;
        $user->save();

        return redirect()->back()->with('success', 'User declined');
    }
public function userlist(User $user)
{
    if (session('role') !== 'admin') {
        abort(403, 'Unauthorized action.');
    }

    // Exclude users with the 'admin' role
    $users = User::where('role', '!=', 'admin')->get();

    return view('auth.userlist', compact('users'));
}


    public function DeleteUser(User $user)
    {
        if (session('role') !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
    public function editUser(User $user)
    {
        if (session('role') !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $user = User::findOrFail($user->id);
        return view('auth.edituser', compact('user'));
        
    }
    public function editUserUpdate(Request $request, User $user)
    {
        if (session('role') !== 'admin') {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'number' => 'required|string|max:10',
        'status' => 'required|in:0,1,2',
        'new_password' => 'nullable|string|min:8|regex:/[a-zA-Z]/|regex:/[0-9]/',
    ]);

    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->number = $request->input('number');
    $user->status = $request->input('status');

    if ($request->filled('new_password')) {
        $user->password = Hash::make($request->input('new_password'));
    }

    $user->save();

    return redirect()->route('userlist')->with('success', 'User updated successfully.');
    }


}
