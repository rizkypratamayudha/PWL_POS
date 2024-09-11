<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class kategoriseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=[
            [
                'kategori_id'=>1,
                'kategori_kode'=>'fnb',
                'kategori_nama'=>'Food and Baverage',                
            ],
            [
                'kategori_id'=>2,
                'kategori_kode'=>'bnh',
                'kategori_nama'=>'Beauty and Health', 
            ],
            [
                'kategori_id'=>3,
                'kategori_kode'=>'bnk',
                'kategori_nama'=>'Baby and Kid', 
            ],
            [
                'kategori_id'=>4,
                'kategori_kode'=>'hnc',
                'kategori_nama'=>'Home and Care', 
            ],
            [
                'kategori_id'=>5,
                'kategori_kode'=>'elk',
                'kategori_nama'=>'Elektronik', 
            ]
        ];
        DB::table('m_kategori')->insert($data);
    }
}
