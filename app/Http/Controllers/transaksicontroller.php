<?php

namespace App\Http\Controllers;

use App\Models\barangmodel;
use App\Models\detail_penjualanmodel;
use App\Models\penjualanmodel;
use App\Models\stokmodel;
use App\Models\usermodel;
use Barryvdh\DomPDF\Facade\Pdf;
use Database\Seeders\detailpenjualanseeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yajra\DataTables\Facades\DataTables;

class transaksicontroller extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar transaksi penjualan',
            'list' => ['Home', 'transaksi penjualan']
        ];

        $page = (object)[
            'title' => 'Daftar transaksi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penjualan';
        $user = usermodel::all();
        return view('transaksi.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'user' => $user]);
    }

    public function list(Request $request)
    {
        $penjualan = penjualanmodel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with('user');

        if ($request->user_id) {
            $penjualan->where('user_id', $request->user_id);
        }

        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan) {
                $btn  = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                // $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id .
                //     '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $user = usermodel::select('user_id', 'nama')->get();
        $barang = barangmodel::select('barang_id', 'barang_nama')->get();
        return view('transaksi.create_ajax', ['user' => $user, 'barang' => $barang]);
    }

    public function store_ajax(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|numeric',
        'pembeli' => 'required|string|min:3|max:20',
        'penjualan_kode' => 'required|string|min:3|max:100|unique:t_penjualan,penjualan_kode',
        'penjualan_tanggal' => 'required|date',
        'barang_id' => 'required|array',
        'barang_id.*' => 'numeric',
        'harga' => 'required|array',
        'harga.*' => 'numeric',
        'jumlah' => 'required|array',
        'jumlah.*' => 'numeric|min:1', // Pastikan jumlah minimal 1
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'msgField' => $validator->errors()
        ]);
    }

    DB::beginTransaction(); // Mulai transaksi

    try {
        $penjualan = new penjualanmodel;
        $penjualan->user_id = $request->user_id;
        $penjualan->pembeli = $request->pembeli;
        $penjualan->penjualan_kode = $request->penjualan_kode;
        $penjualan->penjualan_tanggal = $request->penjualan_tanggal;
        $penjualan->save();

        foreach ($request->barang_id as $index => $barangId) {
            $jumlah = $request->jumlah[$index];

            $stokBarang = DB::table('t_stok')
                ->where('barang_id', $barangId)
                ->value('stok_jumlah');

            if ($jumlah > $stokBarang) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => "Jumlah yang diminta untuk barang ID $barangId melebihi stok yang tersedia.",
                ]);
            }

            $detailpenjualan = new detail_penjualanmodel;
            $detailpenjualan->penjualan_id = $penjualan->penjualan_id;
            $detailpenjualan->barang_id = $barangId;
            $detailpenjualan->harga = $request->harga[$index];
            $detailpenjualan->jumlah = $jumlah;
            $detailpenjualan->save();


            DB::table('t_stok')
                ->where('barang_id', $barangId)
                ->decrement('stok_jumlah', $jumlah);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Data penjualan berhasil disimpan!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack(); // Rollback transaksi jika ada kesalahan
        return response()->json([
            'status' => false,
            'message' => 'Terjadi kesalahan, data gagal disimpan!',
            'error' => $e->getMessage()
        ]);
    }
}




    public function confirm_ajax(string $penjualan_id)
    {
        $penjualan = penjualanmodel::find($penjualan_id);

        $detailpenjualan = detail_penjualanmodel::where('penjualan_id', $penjualan_id)->get();

        return view('transaksi.confirm_ajax', [
            'penjualan' => $penjualan,
            'detailpenjualan' => $detailpenjualan
        ]);
    }

    public function show_ajax(string $penjualan_id)
    {
        $penjualan = penjualanmodel::find($penjualan_id);

        $detailpenjualan = detail_penjualanmodel::where('penjualan_id', $penjualan_id)->get();

        return view('transaksi.show_ajax', [
            'penjualan' => $penjualan,
            'detailpenjualan' => $detailpenjualan
        ]);
    }

    public function delete_ajax(Request $request, $penjualan_id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $user = penjualanmodel::find($penjualan_id);

            if ($user) {
                try {
                    detail_penjualanmodel::where('penjualan_id', $penjualan_id)->delete();
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

    public function import()
    {
        return view('transaksi.import');
    }

    public function getHarga($id)
    {
        $barang = barangmodel::find($id); // Assuming you have a Barang model
        return response()->json(['harga_jual' => $barang->harga_jual]);
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_penjualan' => ['required', 'mimes:xlsx', 'max:1024'] // Validate file
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_penjualan'); // Get the file from the request
            $reader = IOFactory::createReader('Xlsx'); // Load Excel reader
            $reader->setReadDataOnly(true); // Only read data
            $spreadsheet = $reader->load($file->getRealPath()); // Load Excel file
            $sheet = $spreadsheet->getActiveSheet(); // Get active sheet

            $data = $sheet->toArray(null, false, true, true); // Convert sheet data to array

            $insert = [];
            $insertDetail = [];

            if (count($data) > 1) {
                foreach ($data as $rowIndex => $row) {
                    if ($rowIndex > 1) { // Skip header row
                        $penjualan_kode = $row['C'];
                        $penjualan_tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['D'])->format('Y-m-d H:i:s');

                        // Check if penjualan_kode already exists
                        $penjualan = penjualanmodel::where('penjualan_kode', $penjualan_kode)->first();

                        if (!$penjualan) {
                            // Create new penjualan record if it doesn't exist
                            $penjualan = new penjualanmodel();
                            $penjualan->user_id = $row['A'];
                            $penjualan->pembeli = $row['B'];
                            $penjualan->penjualan_kode = $penjualan_kode;
                            $penjualan->penjualan_tanggal = $penjualan_tanggal; // Use date from Excel
                            $penjualan->created_at = now();
                            $penjualan->save(); // Save the main penjualan record
                        }

                        // Create detail penjualan record
                        $insertDetail[] = [
                            'penjualan_id' => $penjualan->penjualan_id, // Get the newly created or existing penjualan ID
                            'barang_id' => $row['E'],
                            'jumlah' => $row['F'],
                            'harga' => $row['G'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insertDetail) > 0) {
                    // Insert detail records to the database
                    detail_penjualanmodel::insertOrIgnore($insertDetail);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data imported successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No data to import'
                ]);
            }
        }
        return redirect('/'); // Redirect if not an AJAX request
    }

    public function export_excel()
    {
        // Ambil data penjualan yang akan diexport
        $penjualan = penjualanmodel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with(['user', 'detailPenjualan.barang'])
            ->orderBy('penjualan_tanggal')
            ->get();

        // Load library excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

        // Set header untuk penjualan
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal Penjualan');
        $sheet->setCellValue('C1', 'User ID');
        $sheet->setCellValue('D1', 'Nama Pembeli');
        $sheet->setCellValue('E1', 'Kode Penjualan');
        $sheet->setCellValue('F1', 'Barang ID');
        $sheet->setCellValue('G1', 'Nama Barang');
        $sheet->setCellValue('H1', 'Jumlah');
        $sheet->setCellValue('I1', 'Harga');

        $sheet->getStyle('A1:I1')->getFont()->setBold(true); // Bold header

        $no = 1;  // Nomor data dimulai dari 1
        $baris = 2; // Baris data dimulai dari baris ke 2

        // Loop untuk setiap penjualan
        foreach ($penjualan as $penj) {
            // Loop untuk setiap detail penjualan
            foreach ($penj->detailPenjualan as $detail) {
                $sheet->setCellValue('A' . $baris, $no);
                $sheet->setCellValue('B' . $baris, $penj->penjualan_tanggal); // Tanggal penjualan
                $sheet->setCellValue('C' . $baris, $penj->user->nama); // Nama user
                $sheet->setCellValue('D' . $baris, $penj->pembeli); // Nama pembeli
                $sheet->setCellValue('E' . $baris, $penj->penjualan_kode); // Kode penjualan
                $sheet->setCellValue('F' . $baris, $detail->barang_id); // Barang ID
                $sheet->setCellValue('G' . $baris, $detail->barang->barang_nama); // Nama barang
                $sheet->setCellValue('H' . $baris, $detail->jumlah); // Jumlah barang
                $sheet->setCellValue('I' . $baris, $detail->harga); // Harga per barang

                $baris++;
                $no++;
            }
        }

        // Set auto size untuk kolom
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set title sheet
        $sheet->setTitle('Data Penjualan');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Penjualan ' . date('Y-m-d H:i:s') . '.xlsx';

        // Pengaturan header untuk download file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf(){
        $penjualan = penjualanmodel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with(['user', 'detailPenjualan.barang'])
            ->orderBy('user_id')
            ->get();

        $pdf = Pdf::loadView('transaksi.export_pdf',['penjualan'=>$penjualan]);
        $pdf->setPaper('a4','portrait'); //set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Penjualan '.date('Y-m-d H:i:s'));
    }

    public function getStok($barang_id) {
        $stok = stokmodel::all()
            ->where('barang_id', $barang_id)
            ->sum('stok_jumlah'); // Mengambil total stok untuk barang yang dipilih

        return response()->json(['stok_jumlah' => $stok]);
    }


}
