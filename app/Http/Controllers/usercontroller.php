<?php

namespace App\Http\Controllers;

use App\Models\levelmodel;
use App\Models\usermodel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yajra\DataTables\DataTables;

class usercontroller extends Controller
{
    //menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar user',
            'list' => ['Home', 'User'],
        ];

        $page = (object)[
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; //set menu yang aktif
        $level = levelmodel::all(); //mengambil data level untuk filter level
        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'level' => $level]);
    }

    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');

        // Filter data user berdasarkan level_id
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
                /*$btn = '<a href="' . url('/user/' . $user->user_id) . '" class="btn btn-info btn-sm">Detail</a> ';
            $btn .= '<a href="' . url('/user/' . $user->user_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
            $btn .= '<form class="d-inline-block" method="POST" action="' . url('/user/' . $user->user_id) . '">'
                . csrf_field() . method_field('DELETE') .
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';*/
                $btn  = '<button onclick="modalAction(\'' . url('/user/' . $user->user_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah User Baru'
        ];

        $level = levelmodel::all();
        $activeMenu = 'user';

        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer',
        ]);

        usermodel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    public function show(string $id)
    {
        $user = usermodel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail user',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail user'
        ];

        $activeMenu = 'user';
        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id)
    {
        $user = usermodel::find($id);
        $level = levelmodel::all();

        $breadcrumb = (object)[
            'title' => 'Edit user',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit User'
        ];

        $activeMenu = 'user';

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu, 'user' => $user]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter,
            // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama' => 'required|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'required|integer'
        ]);

        $user = UserModel::find($id);

        $user->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'level_id' => $request->level_id
        ]);
        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = usermodel::find($id);
        if (!$check) {
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }

        try {
            usermodel::destroy($id);
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/user')->with('error', 'Data user gagal dhapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax()
    {
        $level = levelmodel::select('level_id', 'level_nama')->get();

        return view('user.create_ajax')->with('level', $level);
    }

    public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:6'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        // Hash the password before saving the user
        $data = $request->all();
        $data['password'] = Hash::make($request->password); // Hash the password

        usermodel::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Data user berhasil disimpan'
        ]);
    }
    return redirect('/');
}

    public function edit_ajax(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    }


public function update_ajax(Request $request, $id)
{
    // cek apakah request dari ajax
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
            'nama'     => 'required|max:100',
            'password' => 'nullable|min:6|max:20' // Password masih bisa diisi atau dibiarkan kosong
        ];

        // Validasi request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => false, // respon json, true: berhasil, false: gagal
                'message'  => 'Validasi gagal.',
                'msgField' => $validator->errors() // menunjukkan field mana yang error
            ]);
        }

        $user = UserModel::find($id);
        if ($user) {
            // Jika password diisi, hash password sebelum menyimpan
            if ($request->filled('password')) {
                $request->merge(['password' => Hash::make($request->password)]);
            } else {
                // Jika tidak diisi, hapus password dari request
                $request->request->remove('password');
            }

            // Update data user
            $user->update($request->all());
            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    return redirect('/');
}


    public function confirm_ajax(string $id)
    {
        $user   = usermodel::find($id);

        return view('user.confirm_ajax', ['user' => $user]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $user = usermodel::find($id);

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

    public function show_ajax(string $id)
    {
        $user   = usermodel::find($id);

        return view('user.show_ajax', ['user' => $user]);
    }

    public function import(){
        return view('user.import');
    }

    public function import_ajax(Request $request){
        if($request->ajax() || $request->wantsJson()){
            $rules = [
                //validasi file harus format apa
                'file_user'=>['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=> 'Validasi gagal',
                    'msgField'=> $validator->errors()
                ]);
            }
            $file = $request ->file('file_user'); //mengambil dari file request
            $reader = IOFactory::createReader('Xlsx'); //load reader file excel
            $reader->setReadDataOnly(true); //hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); //load file excel
            $sheet = $spreadsheet->getActiveSheet(); //ambil sheetyang

            $data =$sheet->toArray(null, false, true, true);

            $insert = [];
            if(count($data) > 1){
                foreach ($data as $baris => $value){
                    if ($baris > 1){
                        $insert[] = [
                            'level_id' => $value['A'],
                            'username' => $value['B'],
                            'nama' => $value['C'],
                            'password' => Hash::make($value['D']),
                            'created_at' => now(),
                        ];
                    }
                }
                if(count($insert) > 0){
                    //inser data ke databasse, ika data sudah ada, maka diabaikan
                    usermodel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status'=>true,
                    'message'=>'Data berhasil diimport'
                ]);
            }
            else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel()
    {
        $user = usermodel::select('level_id', 'username', 'nama')
            ->orderBy('level_id')
            ->with('level')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); //ambil sheet yang aktif

        // Set Header Kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'username');
        $sheet->setCellValue('C1', 'nama');;
        $sheet->setCellValue('D1', 'level');

        // Buat header menjadi bold
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $no = 1; // Nomor data dimulai dari 1
        $baris = 2; // Baris data dimulai dari baris ke-2
        foreach ($user as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->username);
            $sheet->setCellValue('C' . $baris, $value->nama);
            $sheet->setCellValue('D' . $baris, $value->level->level_nama);
            $baris++;
            $no++;
        }

        // Set ukuran kolom otomatis untuk semua kolom
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set judul sheet
        $sheet->setTitle('Data user');

        // Buat writer
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data user ' . date('Y-m-d H:i:s') . '.xlsx';

        // Atur Header untuk Download File Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        // Simpan file dan kirim ke output
        $writer->save('php://output');
        exit;
    }

    public function export_pdf(){
        $user = usermodel::select('level_id','username','nama')
        ->orderBy('level_id')
        ->with('level')->get();

        $pdf = Pdf::loadView('user.export_pdf',['user'=>$user]);
        $pdf->setPaper('a4','portrait'); //set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); //set true jika ada gambar
        $pdf->render();

        return $pdf->stream('Data level '.date('Y-m-d H:i:s'));
    }
}
