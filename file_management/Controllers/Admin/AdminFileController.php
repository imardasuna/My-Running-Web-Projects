<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Access;

class AdminFileController extends Controller
{
    public function index()
    {
        $files = File::get();
        return view('admin.addfile',compact('files')); 
    
    }
    public function addfile(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ]);
        $uploadedFile = $request->file('file');

        // Orijinal dosya adını alıyoruz
        $fileName = $uploadedFile->getClientOriginalName();
        $destinationPath = base_path('../uploads');
        // Hedef yolu belirliyoruz

        // Dosyayı hedefe taşıyoruz
        $uploadedFile->move($destinationPath, $fileName);

        // File modeline dosya bilgilerini kaydediyoruz
        $file = new File();
        $file->name = $fileName;
        $file->save();

        return back()->with('success', 'Dosya başarıyla yüklendi ve kaydedildi.');
    }
    public function delete_File($id)
    {
        $file = File::where('id',$id)->first();

        
    $filePath = base_path('../uploads/'.$file->name);
    unlink($filePath);
        $file->delete();

        return back()->with('success','Dosya silndi');

    }
    public function viewFile(Request $request)
    {
        $name = $request->name;
        return view('front.viewfiles',compact('name'));
    }
    public function manage_access(Request $request)
    {
        $files = $request->input('files', []);
        $userId = $request->input('user_id');

        Access::where('customer_id',$userId)->delete();

        foreach($files as $file)
        {
            $access = new Access();
            $access->customer_id = $userId;
            $access->file_id = $file;
            $access->save();
        }
        return back()->with('success', 'Kullanıcının dosya erişimi düzenlendi');
    }
    public function userviewfiles()
    {
        
        $id = Auth::guard('customer')->user()->id;
        $accesses = Access::where('customer_id', $id)->pluck('file_id');
        $files = File::whereIn('id', $accesses)->get();

        return view('front.files',compact('files'));
    }   
}
