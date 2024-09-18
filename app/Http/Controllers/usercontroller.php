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

        // $data = [
        //     'nama'=> 'Pelanggan Pertama',
        // ];
        // usermodel::where('username','customer-1')->update($data);//update data user

        // $user = usermodel::all(); //mengambil semua data dari table m_user
        // return view('user',['data'=>$user]);


        //Praktikum 4
        // $data = [
        //     'level_id' => 2,
        //     'username' => 'manager_tiga',
        //     'nama' => 'Manager 3',
        //     'password' => Hash::make('12345')
        // ];
        // usermodel::create($data);
        // $user = usermodel::all();
        // return view('user',['data'=>$user]);

        // $user = usermodel::where('level_id',2)->count();
        // return view('user', ['data'=>$user]);

        // $user = usermodel::firstOrNew(
        //     [
        //         'username'=>'manager33',
        //         'nama'=>'Manager Tiga Tiga',
        //         'password'=> Hash::make('12345'),
        //         'level_id'=>2
        //     ]
        // );
        // $user->save();
        // return view('user',['data'=>$user]);

        //praktikum 2.5
        // $user = usermodel::create([
        //     'username'=>'manager55',
        //     'nama'=>'Manager55',
        //     'password'=>Hash::make('12345'),
        //     'level_id'=>2
        // ]);

        // $user->username = 'manager56';

        // $user->isDirty();//true
        // $user->isDirty('username');//true
        // $user->isDirty('nama');//true
        // $user->isDirty(['nama','username']);//true

        // $user->isClean();//false
        // $user->isClean('username');//false
        // $user->isClean('nama');//false
        // $user->isClean(['nama','username']);//false

        // $user->save();

        // $user->isDirty();//false
        // $user->isClean();//true
        // dd($user->isDirty());

        // $user = usermodel::create([
        //     'username'=>'manager11',
        //     'nama'=>'Manager11',
        //     'password'=>Hash::make('12345'),
        //     'level_id'=>2
        // ]);

        // $user->username = 'manager12';

        // $user->save();

        // $user->wasChanged();//true
        // $user->wasChanged('username');//true
        // $user->wasChanged(['username','level_id']);//true
        // $user->wasChanged('nama');//true
        // $user->wasChanged(['nama','username']);//true
        // dd($user->wasChanged(['nama','username']));

        $user = usermodel::with('level')->get();
        return view('user',['data'=>$user]);
    }

    public function tambah(){
        return view('user_tambah');
        
    }

    public function tambah_simpan(Request $request){
        usermodel::create(
            [
                'username'=>$request->username,
                'nama'=>$request->nama,
                'password' =>Hash::make('$request->password'),
                'level_id'=> $request->level_id
            ]
        );
        return redirect('/user');
    }

    public function ubah($id){
        $user = usermodel::find($id);
        return view ('user_ubah',['data' =>$user]);
    }

    public function ubah_simpan($id, Request $request){
        $user = usermodel::find($id);

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make('$request->password');
        $user->level_id = $request->level_id;

        $user->save();

        return redirect('/user');

    }

    public function hapus($id){
        $user = usermodel::find($id);
        $user->delete();

        return redirect('/user');
    }
}
