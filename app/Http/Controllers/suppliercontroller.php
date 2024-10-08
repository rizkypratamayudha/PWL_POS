<?php

namespace App\Http\Controllers;

use App\Models\suppliermodel;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class suppliercontroller extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title'=>'Daftar supplier',
            'list'=>['Home','supplier']
        ];
        $page =(object)[
            'title'=>'Daftar supplier yang terdaftar dalam sistem'
        ];
        $activeMenu ='supplier';
        $supplier = suppliermodel::all();
        return view('supplier.index',['breadcrumb'=>$breadcrumb,'page'=>$page,'supplier'=>$supplier, 'activeMenu' =>$activeMenu]);
    }

    public function list(Request $request){
        $supplier = suppliermodel::select('supplier_id','supplier_kode','supplier_nama','supplier_alamat');
        if($request->supplier_id){
            $supplier->where('supplier_id',$request->supplier_id);
        }
        return DataTables::of($supplier)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) { // menambahkan kolom aksi
                // $btn = '<a href="' . url('/supplier/' . $supplier->supplier_id) . '" class="btn btn-info btnsm">Detail</a> ';
                // $btn .= '<a href="' . url('/supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/supplier/' . $supplier->supplier_id) . '">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                // return $btn;
                $btn  = '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
    public function create(){
        $breadcrumb = (object)[
            'title'=>'Tambah supplier',
            'list'=>['Home','supplier','tambah']
        ];
        $page = (object)[
            'title'=>'Tambah supplier baru'
        ];
        $activeMenu = 'supplier';
        $supplier = suppliermodel::all();
        return view('supplier.create',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'supplier'=>$supplier]);
    }

    public function store(Request $request){
        $request->validate([
            'supplier_kode'=>'required|string|min:3|max:5|unique:m_supplier,supplier_kode',
            'supplier_nama'=>'required|string|max:100',
            'supplier_alamat'=>'required|string|max:100'
        ]);
        suppliermodel::create([
            'supplier_kode'=>$request->supplier_kode,
            'supplier_nama'=>$request->supplier_nama,
            'supplier_alamat'=>$request->supplier_alamat,
        ]);
        return redirect('/supplier')->with('success','Data supplier berhasil disimpan');
    }

    public function show(string $supplier_id){
        $supplier = suppliermodel::find($supplier_id);
        $breadcrumb = (object)[
            'title'=>'Detail supplier',
            'list'=>['Home','supplier','detail']
        ];
        $page = (object)[
            'title'=>'Detail supplier'
        ];
        $activeMenu = 'supplier';
        return view('supplier.show',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'supplier'=>$supplier]);
    }

    public function edit(string $supplier_id){
        $supplier = suppliermodel::find($supplier_id);
        $breadcrumb = (object)[
            'title'=>'Edit supplier',
            'list'=>['Home','supplier','edit']
        ];
        $page = (object)[
            'title' => 'Edit supplier'
        ];
        $activeMenu = 'supplier';
        return view('supplier.edit',['breadcrumb'=>$breadcrumb,'page'=>$page,'supplier'=>$supplier,'activeMenu'=>$activeMenu]);
    }

    public function update(Request $request, string $supplier_id){
        $request->validate([
            'supplier_kode'=>'required|string|min:3|max:5|unique:m_supplier,supplier_kode',
            'supplier_nama'=>'required|string|max:100',
            'supplier_alamat'=>'required|string|max:100'
        ]);
        $supplier = suppliermodel::find($supplier_id);
        $supplier->update([
            'supplier_kode'=>$request->supplier_kode,
            'supplier_nama'=>$request->supplier_nama,
            'supplier_alamat'=>$request->supplier_alamat
        ]);
        return redirect('/supplier')->with('success','Data supplier berhasil diperbarui');
    }

    public function destroy(string $supplier_id){
        $check = suppliermodel::find($supplier_id);
        if (!$check) {
            return redirect('/supplier')->with('error', 'Data level tidak ditemukan');
        }
        try {
            suppliermodel::destroy($supplier_id);
            return redirect('/supplier')->with('success', 'Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/supplier')->with('error', 'Data level gagal dhapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax(){
        return view('supplier.create_ajax');
    }

    public function store_ajax(Request $request){
        if($request->json()||$request->wantsJson()){
            $rules = [
            'supplier_kode'=>'required|string|min:3|max:5|unique:m_supplier,supplier_kode',
            'supplier_nama'=>'required|string|max:100',
            'supplier_alamat'=>'required|string|max:100'
            ];
            $validator = Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'Validasi Gagal',
                    'msgField'=>$validator->errors()
                ]);
            }
            suppliermodel::create($request->all());
            return response()->json([
                'status'=>true,
                'message'=>'Data supplier berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $supplier_id){
        $supplier = suppliermodel::find($supplier_id);
        return view('supplier.edit_ajax',['supplier'=>$supplier]);
    }

    public function update_ajax(Request $request, $supplier_id){
        if($request->ajax()||$request->wantsJson()){
            $rules = [
                'supplier_kode'=>'required|string|min:3|max:5|unique:m_supplier,supplier_kode',
                'supplier_nama'=>'required|string|max:100',
                'supplier_alamat'=>'required|string|max:100'
            ];
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json([
                    'status' =>false,
                    'message'=>'Validasi gagal',
                    'msgField'=>$validator->errors()
                ]);
            }
            $check = suppliermodel::find($supplier_id);
            if($check){
                $check->update($request->all());
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

    public function confirm_ajax(string $supplier_id){
        $supplier = suppliermodel::find($supplier_id);
        return view('supplier.confirm_ajax',['supplier'=>$supplier]);
    }

    public function delete_ajax(Request $request, $supplier_id){
        if ($request->ajax() || $request->wantsJson()) {
            $user = suppliermodel::find($supplier_id);
            
            if ($user) {
                try {
                    $user->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
    
        return redirect('/');
    }

    public function show_ajax(string $supplier_id){
        $supplier = suppliermodel::find($supplier_id);
        return view('supplier.show_ajax',['supplier'=>$supplier]);
    }
}
