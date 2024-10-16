<?php

namespace App\Http\Controllers;

use App\Models\levelmodel;
use App\Models\usermodel;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Storage;

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
        $avatar = $request->file('avatar')->store('avatars');
        $request->user()->update([
            'avatar' =>$avatar,
        ]);
        return redirect()->back();
    }


}
