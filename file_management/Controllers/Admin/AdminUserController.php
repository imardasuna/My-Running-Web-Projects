<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\File;
use App\Models\Access;
use Auth;
use Hash;
class AdminUserController extends Controller
{
    public function view()
    {
        return view('admin.adduser');
    }
    public function add_user(Request $request)
    {
        $obj = new Customer();
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);
        $Hashedpassword = Hash::make($request->password);

        $obj->username = $request->name;
        $obj->name = $request->name;
        $obj->email = $request->email;
        $obj->password = $Hashedpassword;
        $obj->save();

        return redirect()->back()->with('success','Kullanıcı eklendi');
    }
    public function viewusers()
    {
        $accesses = Access::get();
        $files = File::get();
        $users = Customer::get();
        return view('admin.viewusers',compact('users','files','accesses'));       
    }
    public function deleteuser($id)
    {
        $user = Customer::find($id);
        if ($user) {
        $user->delete();
        }
        
    }
}
