<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class detail_penjualanmodel extends Model
{
    protected $table = 't_penjualan_detail';

    protected $primaryKey = 'detail_id';

    protected $fillable =['detail_id','penjualan_id','barang_id','harga','jumlah'];

    public function penjualan():BelongsTo{
        return $this->belongsTo(penjualanmodel::class,'penjualan_id','penjualan_id');
    }

    public function barang():BelongsTo{
        return $this->belongsTo(barangmodel::class,'barang_id','barang_id');
    }
}
