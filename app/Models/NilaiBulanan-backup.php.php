<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiBulanan extends Model
{
    use HasFactory;

    protected $table = 'nilai_bulanans';

    /**
     * Kolom yang boleh diisi
     */
    protected $fillable = [
        'siswa_id',
        'pembelajaran_id',
        'bulan',
        'semester',
        'tahun',
        'nilai',
        'capaian_tp_optimal',
        'capaian_tp_kurang',
        'deskripsi',
    ];

    /**
     * Relasi ke siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Relasi ke pembelajaran
     */
    public function pembelajaran()
    {
        return $this->belongsTo(Pembelajaran::class);
    }

    /**
     * Scope helper: filter bulanan
     */
    public function scopeBulanan($query, $bulan, $semester, $tahun)
    {
        return $query
            ->where('bulan', $bulan)
            ->where('semester', $semester)
            ->where('tahun', $tahun);
    }
}