<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Auth;
use Hash;


class UserLoginController extends Controller
{
    public function login_submit(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);
        
        $customer = \App\Models\Customer::where('email', $request->login)
        ->orWhere('username', $request->login)
        ->first();

        if (!$customer) {
        return redirect()->back()->with('error', __('validation.login_not_found'));
        }

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credential = [
            $loginField => $request->login,
            'password' => $request->password
        ];

        if(Auth::guard('customer')->attempt($credential)){
            return redirect()->route('userviewfiles');
        } else {
            // Debugging için eklenen kodlar
            $customer = Customer::where('email', $request->email)->first();
            if ($customer) {
                if (Hash::check($request->password, $customer->password)) {
                    return redirect()->route('login')->with('error', 'Authentication failed for an unknown reason.');
                } else {
                    return redirect()->route('login')->with('error', 'Password is incorrect.');
                }
            } else {
                return redirect()->route('login')->with('error', 'Email not found.');
            }
        }
    }
    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('login');
    }  
}
