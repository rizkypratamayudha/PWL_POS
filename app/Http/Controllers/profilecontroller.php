<?php

namespace App\Http\Controllers;

use App\Models\levelmodel;
use App\Models\usermodel;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class profilecontroller extends Controller
{
    public function edit(){
        $activeMenu = '';
        $breadcrumb = (object)[
            'title'=>'Edit Profile',
            'list'=>['Home','Edit Profile'],
        ];
        $page = (object)[
            'title'=>'Edit Profile'
        ];
        $level = levelmodel::all();
        return view('profile.index', ['activeMenu'=> $activeMenu, 'breadcrumb'=>$breadcrumb,'page'=>$page, 'level'=>$level]);
    }

    public function update(Request $request){

        if ($request->user()->avatar){
            Storage::delete($request->user()->avatar);
        }
        $avatar = $request->file('avatar')->store('avatars');
        $request->user()->update([
            'avatar' =>$avatar,
        ]);
        return redirect()->back();
    }


    public function delete(Request $request){
        if ($request->user()->avatar){
            Storage::delete($request->user()->avatar);
        }
        $request->user()->update([
            'avatar' => null
        ]);

        return redirect()->back();
    }

    public function updateinfo(Request $request){
        if($request->ajax() || $request->wantsJson()){
            $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|max:20|unique:m_user,username,' . $request->user()->user_id . ',user_id',
            'nama'     => 'nullable|max:100',
            'password' => 'nullable|min:6|max:20'
            ];

            $validator = Validator::make($request->all(),$rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false, // respon json, true: berhasil, false: gagal
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }

            $user = $request->user();

            if ($user){
                if ($request->filled('password')) {
                    $request->merge(['password' => Hash::make($request->password)]);
                } else {
                    // Jika tidak diisi, hapus password dari request
                    $request->request->remove('password');
                }

                $user->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            }
            else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

}
