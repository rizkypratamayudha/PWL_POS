<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class usermodel extends Model
{
    use HasFactory;

    protected $table = 'm_user'; //mendefinisikan nama tabel yang digunakan oleh model
    protected $primaryKey = 'user_id'; //mendefinisikan primary key dari table yang digunakan

    protected $fillable = ['level_id','username','nama','password'];
    
    public function level():BelongsTo{
        return $this->belongsTo(levelmodel::class,'level_id', 'level_id');
    }
}
