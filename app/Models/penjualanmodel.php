<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class penjualanmodel extends Model
{
    protected $table = 't_penjualan';

    protected $primaryKey = 'penjualan_id';

    protected $fillable = ['penjualan_id','user_id','pembeli','penjualan_kode','penjualan_tanggal'];

    public function user():BelongsTo{
        return $this->belongsTo(usermodel::class,'user_id','user_id');
    }

    public function barang(){
        return $this->hasManyThrough(barangmodel::class,detail_penjualanmodel::class,'penjualan_id','barang_id','penjualan_id','barang_id');
    }

    public function detailPenjualan()
{
    return $this->hasMany(detail_penjualanmodel::class, 'penjualan_id','penjualan_id');
}

}
