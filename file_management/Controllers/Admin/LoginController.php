<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;
use Hash;

class LoginController extends Controller
{
    public function create_table()
    {

        Admin::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
        ]);
        $password = Hash::make("12345");
        
        $obj = new Admin();
        $obj->name = "haluk";
        $obj->password = $password;
        $obj->email = "haluksuna@gmail.com";
        $obj->save();
        
        session()->flash('success', 'eklendi');
        return redirect()->back()->with('success','ürün sepete eklendi');


    }
    public function index()
    {
        return view('admin.login');
    }
    public function login_submit(Request $request)
    {
        
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credential = [
            'email' => $request->login,
            'password' => $request->password
        ];

        if(Auth::guard('admin')->attempt($credential)){
            return redirect()->route('admin_home');
        }
        else{
            return redirect()->route('admin_login')->with('error','Information is not correct');
        }
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin_login');
    }   

}
