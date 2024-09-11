<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class penjualanseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'penjualan_id'=>1,
                'user_id'=>3,
                'pembeli'=>'Yudha',
                'penjualan_kode'=>'aaa',
                'penjualan_tanggal'=>'2024-09-11',
            ],
            [
                'penjualan_id'=>2,
                'user_id'=>3,
                'pembeli'=>'Rizky',
                'penjualan_kode'=>'aab',
                'penjualan_tanggal'=>'2024-09-11',
            ],
            [
                'penjualan_id'=>3,
                'user_id'=>3,
                'pembeli'=>'Gelby',
                'penjualan_kode'=>'aba',
                'penjualan_tanggal'=>'2024-09-11',
            ],
            [
                'penjualan_id'=>4,
                'user_id'=>3,
                'pembeli'=>'Solikhin',
                'penjualan_kode'=>'abc',
                'penjualan_tanggal'=>'2024-09-11',
            ],
            [
                'penjualan_id'=>5,
                'user_id'=>3,
                'pembeli'=>'Yudha',
                'penjualan_kode'=>'acb',
                'penjualan_tanggal'=>'2024-09-11',
            ],
            [
                'penjualan_id'=>6,
                'user_id'=>3,
                'pembeli'=>'Yudha',
                'penjualan_kode'=>'acc',
                'penjualan_tanggal'=>'2024-09-11',
            ],
            [
                'penjualan_id'=>7,
                'user_id'=>3,
                'pembeli'=>'Yudha',
                'penjualan_kode'=>'ana',
                'penjualan_tanggal'=>'2024-09-11',
            ],
            [
                'penjualan_id'=>8,
                'user_id'=>3,
                'pembeli'=>'Yudo',
                'penjualan_kode'=>'ama',
                'penjualan_tanggal'=>'2024-09-11',
            ],
            [
                'penjualan_id'=>9,
                'user_id'=>3,
                'pembeli'=>'Yudha',
                'penjualan_kode'=>'omo',
                'penjualan_tanggal'=>'2024-09-11',
            ],
            [
                'penjualan_id'=>10,
                'user_id'=>3,
                'pembeli'=>'Yudha',
                'penjualan_kode'=>'kon',
                'penjualan_tanggal'=>'2024-09-11',
            ]
        ];
        DB::table('t_penjualan')->insert($data);
    }
}
