<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class barangseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id'=>1,
                'kategori_id'=>1,
                'barang_kode'=>'aym',
                'barang_nama'=>'Ayam',
                'harga_beli'=>8000,
                'harga_jual'=>10000
            ],
            [
                'barang_id'=>2,
                'kategori_id'=>1,
                'barang_kode'=>'ikn',
                'barang_nama'=>'Ikan',
                'harga_beli'=>10000,
                'harga_jual'=>12000
            ],
            [
                'barang_id'=>3,
                'kategori_id'=>1,
                'barang_kode'=>'saw',
                'barang_nama'=>'Sawi',
                'harga_beli'=>2000,
                'harga_jual'=>5000
            ],
            [
                'barang_id'=>4,
                'kategori_id'=>2,
                'barang_kode'=>'prc',
                'barang_nama'=>'Paracetamol',
                'harga_beli'=>8000,
                'harga_jual'=>10000
            ],
            [
                'barang_id'=>5,
                'kategori_id'=>2,
                'barang_kode'=>'sun',
                'barang_nama'=>'Sunscreen',
                'harga_beli'=>10000,
                'harga_jual'=>12000
            ],
            [
                'barang_id'=>6,
                'kategori_id'=>2,
                'barang_kode'=>'dcg',
                'barang_nama'=>'Decolgen',
                'harga_beli'=>8000,
                'harga_jual'=>10000
            ],
            [
                'barang_id'=>7,
                'kategori_id'=>3,
                'barang_kode'=>'bed',
                'barang_nama'=>'Bedak Bayi',
                'harga_beli'=>15000,
                'harga_jual'=>19000
            ],
            [
                'barang_id'=>8,
                'kategori_id'=>3,
                'barang_kode'=>'pop',
                'barang_nama'=>'Popok Bayi',
                'harga_beli'=>8000,
                'harga_jual'=>20000
            ],
            [
                'barang_id'=>9,
                'kategori_id'=>3,
                'barang_kode'=>'tmp',
                'barang_nama'=>'Tempat Makan Bayi',
                'harga_beli'=>8000,
                'harga_jual'=>10000
            ],
            [
                'barang_id'=>10,
                'kategori_id'=>4,
                'barang_kode'=>'sap',
                'barang_nama'=>'Sapu',
                'harga_beli'=>8000,
                'harga_jual'=>10000
            ],
            [
                'barang_id'=>11,
                'kategori_id'=>4,
                'barang_kode'=>'pel',
                'barang_nama'=>'Pel',
                'harga_beli'=>8000,
                'harga_jual'=>10000
            ],
            [
                'barang_id'=>12,
                'kategori_id'=>4,
                'barang_kode'=>'pem',
                'barang_nama'=>'Pembersih',
                'harga_beli'=>8000,
                'harga_jual'=>10000
            ],
            [
                'barang_id'=>13,
                'kategori_id'=>5,
                'barang_kode'=>'lmp',
                'barang_nama'=>'Lampu',
                'harga_beli'=>8000,
                'harga_jual'=>10000
            ],
            [
                'barang_id'=>14,
                'kategori_id'=>5,
                'barang_kode'=>'kbl',
                'barang_nama'=>'Kabel',
                'harga_beli'=>8000,
                'harga_jual'=>10000
            ],
            [
                'barang_id'=>15,
                'kategori_id'=>5,
                'barang_kode'=>'fit',
                'barang_nama'=>'Fitting',
                'harga_beli'=>8000,
                'harga_jual'=>10000
            ]
        ];
        DB::table('m_barang')->insert($data);
    }
}
