<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\detail_penjualanmodel;
use App\Models\penjualanmodel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function index()
{
    $penjualan = penjualanmodel::with('detailPenjualan.barang')->get();
    return response()->json($penjualan);
}

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|numeric',
        'pembeli' => 'required|string|min:3|max:20',
        'penjualan_kode' => 'required|string|min:3|max:100|unique:t_penjualan,penjualan_kode',
        'penjualan_tanggal' => 'required|date',
        'barang_id' => 'required|array',
        'barang_id.*' => 'numeric',
        'jumlah' => 'required|array',
        'jumlah.*' => 'numeric|min:1', // Ensure quantity is at least 1
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'msgField' => $validator->errors()
        ]);
    }

    DB::beginTransaction(); // Start transaction

    try {
        $penjualan = new penjualanmodel;
        $penjualan->user_id = $request->user_id;
        $penjualan->pembeli = $request->pembeli;
        $penjualan->penjualan_kode = $request->penjualan_kode;
        $penjualan->penjualan_tanggal = $request->penjualan_tanggal;
        $penjualan->save();

        foreach ($request->barang_id as $index => $barangId) {
            $jumlah = $request->jumlah[$index];

            // Fetch the harga from the barang table
            $barang = DB::table('m_barang')->where('barang_id', $barangId)->first();

            if (!$barang) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => "Barang ID $barangId tidak ditemukan.",
                ]);
            }

            $harga = $barang->harga_jual;

            $stokBarang = DB::table('t_stok')->where('barang_id', $barangId)->value('stok_jumlah');

            if ($jumlah > $stokBarang) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => "Jumlah yang diminta untuk barang ID $barangId melebihi stok yang tersedia.",
                ]);
            }

            // Save the detail penjualan
            $detailpenjualan = new detail_penjualanmodel;
            $detailpenjualan->penjualan_id = $penjualan->penjualan_id;
            $detailpenjualan->barang_id = $barangId;
            $detailpenjualan->harga = $harga;
            $detailpenjualan->jumlah = $jumlah;
            $detailpenjualan->save();

            // Decrement stock
            DB::table('t_stok')->where('barang_id', $barangId)->decrement('stok_jumlah', $jumlah);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Data penjualan berhasil disimpan!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack(); // Rollback transaction if there's an error
        return response()->json([
            'status' => false,
            'message' => 'Terjadi kesalahan, data gagal disimpan!',
            'error' => $e->getMessage()
        ]);
    }
}

    public function show(penjualanmodel $id){
        return penjualanmodel::with('detailPenjualan.barang')->find($id);
    }

    public function destroy (penjualanmodel $id){
        $id->detailPenjualan()->delete()    ;
        $id->delete();
        return response()->json([
            'success'=>true,
            'message'=>'Data terhapus'
        ]);
    }
}
