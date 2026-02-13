<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelajaran extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function kelas(){
      return $this->belongsTo(Kelas::class);
    }

    public function mapel(){
      return $this->belongsTo(Mapel::class);
    }

    public function guru(){
      return $this->belongsTo(Guru::class);
    }

    public function tujuanPembelajaran(){
      return $this->hasMany(TujuanPembelajaran::class);
    }
    
    public function tujuanPembelajaranBulanan(){
      return $this->hasMany(TujuanPembelajaranBulanan::class);
    }

    public function nilaiAkhir(){
      return $this->hasMany(NilaiAkhir::class);
    }

    public function nilaiBulanan()
    {
        return $this->hasMany(NilaiBulanan::class);
    }


    // HELPER
    public function guru_pengampu(){
      return ($this->guru) ? $this->guru->name : '-';
    }
}