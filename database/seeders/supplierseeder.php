<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class supplierseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data =[
            [
                'supplier_id'=>1,
                'supplier_kode'=>'glb',
                'supplier_nama'=>'Gelby Supplier',
                'supplier_alamat'=>'Jl Merjosari no 1',
            ],
            [
                'supplier_id'=>2,
                'supplier_kode'=>'tfk',
                'supplier_nama'=>'Taufiq Supplier',
                'supplier_alamat'=>'Jl Mojokerto no 2',
            ],
            [
                'supplier_id'=>3,
                'supplier_kode'=>'slk',
                'supplier_nama'=>'Solikhin Supplier',
                'supplier_alamat'=>'Jl Ngawi no 3',
            ]
        ];
        DB::table('m_supplier')->insert($data);
    }
}
