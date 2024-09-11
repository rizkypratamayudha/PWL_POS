<?php

namespace App\Http\Controllers;

use App\Models\usermodel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class usercontroller extends Controller
{
    public function index(){

        //menambah data user dengan Eloquent Model
        // $data = [
        //     [
        //         'username'=>'customer-2',
        //         'nama'=>'pelanggan',
        //         'password'=> Hash::make('12345'),
        //         'level_id'=>4
        //     ]
        // ];
        // usermodel::insert($data);//menambahkan data ke table m_user

        $data = [
            'nama'=> 'Pelanggan Pertama',
        ];
        usermodel::where('username','customer-1')->update($data);//update data user

        $user = usermodel::all(); //mengambil semua data dari table m_user
        return view('user',['data'=>$user]);
    }
}
