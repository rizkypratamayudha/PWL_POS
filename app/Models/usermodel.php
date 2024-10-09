<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class usermodel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user'; //mendefinisikan nama tabel yang digunakan oleh model
    protected $primaryKey = 'user_id'; //mendefinisikan primary key dari table yang digunakan

    protected $fillable = ['level_id','username','nama','password','created_at','updated_at'];

    protected $hidden = ['password'];

    protected $casts = ['password'=>'hashed'];

    public function level():BelongsTo{
        return $this->belongsTo(levelmodel::class,'level_id', 'level_id');
    }

    public function getRoleName(){
        return $this->level->level_nama;
    }

    public function hasRole($role){
        return $this->level->level_kode == $role;
    }
}
