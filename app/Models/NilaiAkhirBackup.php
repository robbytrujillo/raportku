<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiAkhirBackup extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function siswa(){
      return $this->belongsTo(Siswa::class);
    }

    public function pembelajaran(){
      return $this->belongsTo(Pembelajaran::class);
    }
}
