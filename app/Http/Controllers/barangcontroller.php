<?php

namespace App\Http\Controllers;

use App\Models\barangmodel;
use App\Models\kategorimodel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class barangcontroller extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' =>'Daftar Barang',
            'list'=>['Home','barang']
        ];
        $page = (object)[
            'title'=>'Daftar Barang yang terdaftar dalam sistem'
        ];
        $activeMenu = 'barang';
        $kategori = kategorimodel::all();
        return view('barang.index',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu, 'kategori'=>$kategori]);
    }
    public function list(Request $request){
        $barang = barangmodel::select('barang_id','kategori_id','barang_kode','barang_nama','harga_beli','harga_jual')
        ->with('kategori');

        if($request->kategori_id){
            $barang->where('kategori_id',$request->kategori_id);
        }
        return DataTables::of($barang)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btnsm">Detail</a> ';
                $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create(){
        $breadcrumb =(object)[
            'title'=>'Tambah Barang',
            'list'=>['Home','data barang']
        ];
        $page =(object)[
            'title'=>'Tambah Barang baru'
        ];
        $kategori = kategorimodel::all();
        $activeMenu = 'barang';
        return view('barang.create',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'kategori'=>$kategori]);
    }

    public function store(Request $request){
        $request->validate([
            'kategori_id'=>'required|integer',
            'barang_kode'=>'required|string|min:3|unique:m_barang,barang_kode',
            'barang_nama'=>'required|string|max:100',
            'harga_jual'=>'required|integer',
            'harga_beli'=>'required|integer',
        ]);
        barangmodel::create([
            'kategori_id'=>$request->kategori_id,
            'barang_kode'=>$request->barang_kode,
            'barang_nama'=>$request->barang_nama,
            'harga_jual'=>$request->harga_jual,
            'harga_beli'=>$request->harga_beli,
        ]);

        return redirect('/barang')->with('success','Data barang berhasil disimpan');
    }

    public function show(string $barang_id){
        $barang = barangmodel::with('kategori')->find($barang_id);
        $breadcrumb = (object)[
            'title'=>'Detail barang',
            'list'=>['Home','Data barang','Detail'],
        ];
        $page = (object)[
            'title'=>'Detail data barang'
        ];
        $activeMenu='barang';
        return view('barang.show',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu, 'barang'=>$barang]);
    }

    public function edit(string $barang_id){
        $barang = barangmodel::find($barang_id);
        $kategori = kategorimodel::all();

        $breadcrumb = (object)[
            'title' =>'Edit data barang',
            'list' =>['Home','data barang','edit']
        ];
        $page = (object)[
            'title'=>'Edit data barang'
        ];
        $activeMenu = 'barang';
        return view('barang.edit',['breadcrumb'=>$breadcrumb,'page'=>$page,'barang'=>$barang,'kategori'=>$kategori, 'activeMenu'=>$activeMenu]);
    }

    public function update(Request $request, string $barang_id){
        $request->validate([
            'kategori_id'=>'required|integer',
            'barang_kode'=>'required|string|min:3|unique:m_barang,barang_kode',
            'barang_nama'=>'required|string|max:100',
            'harga_jual'=>'required|integer',
            'harga_beli'=>'required|integer',
        ]);

        $barang = barangmodel::find($barang_id);
        $barang->update([
            'kategori_id'=>$request->kategori_id,
            'barang_kode'=>$request->barang_kode,
            'barang_nama'=>$request->barang_nama,
            'harga_jual'=>$request->harga_jual,
            'harga_beli'=>$request->harga_beli,
        ]);
        return redirect('/barang')->with('success','Data barang berhasil diubah');
    }

    public function destroy(string $barang_id){
        $check = barangmodel::find($barang_id);
        if(!$check){
            return redirect('/barang')->with('error','Data user tidak ditemukan');
        }

        try{
            barangmodel::destroy($barang_id);
            return redirect('/barang')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e){
            return redirect('/barang')->with('error','Data user gagal dhapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}