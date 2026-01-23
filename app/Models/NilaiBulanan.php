<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiBulanan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function siswa(){
        return $this->belongsTo(Siswa::class);
    }

    public function pembelajaran(){
        return $this->belongsTo(Pembelajaran::class);
    }

    // Nilai Bulanan
    public function scopeBulanan($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)
                     ->where('tahun', $tahun);
    }

    // Nilai Semester
    public function scopeSemester($query, $semester, $tahun)
    {
        return $query->where('semester', $semester)
                     ->whereNull('bulan')
                     ->where('tahun', $tahun);
    }
}