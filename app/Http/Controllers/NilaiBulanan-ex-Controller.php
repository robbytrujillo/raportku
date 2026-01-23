<?php

namespace App\Http\Controllers;

use App\Models\NilaiBulanan;
use App\Models\Pembelajaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NilaiBulananController extends Controller
{
    /**
     * Daftar pembelajaran (menu Input Nilai Bulanan)
     */
    public function index(Request $request)
    {
        $pembelajaran = Pembelajaran::latest();

        if ($request->mapel_id) {
            $pembelajaran->where('mapel_id', $request->mapel_id);
        }

        if ($request->kelas_id) {
            $pembelajaran->where('kelas_id', $request->kelas_id);
        }

        if ($request->guru_id) {
            $pembelajaran->where('guru_id', $request->guru_id);
        }

        return view('pages.nilaibulanan.index', [
            'pembelajaran' => $pembelajaran->get()
        ]);
    }

    /**
     * Form input nilai bulanan
     */
    public function show(Pembelajaran $pembelajaran, Request $request)
    {
        $user = Auth::user();

        // dd([
        //     'user_role' => Auth::user()->role,
        //     'is_guru_mapel' => Auth::user()->isGuruMapel(),
        //     'guru_id_login' => Auth::user()->guru->id ?? null,
        //     'guru_id_pembelajaran' => $pembelajaran->guru_id,
        // ]);


        // if (!$user->isGuruMapel() || $user->guru->id !== $pembelajaran->guru_id) {
        //     abort(403);
        // }

        // if (!$user->isGuruMapel()) {
        //     abort(403);
        // }

        // if ($pembelajaran->guru_id && $user->guru->id != $pembelajaran->guru_id) {
        //     abort(403);
        // }

        if (!$user->isGuruMapel()) {
            abort(403);
        }

        // $bulan    = $request->bulan;
        // $semester = $request->semester;
        // $tahun    = $request->tahun;

        $bulan    = $request->bulan ?? now()->month;
        $semester = $request->semester ?? 1;
        $tahun    = $request->tahun ?? now()->year;

        $siswa = Siswa::where('kelas_id', $pembelajaran->kelas_id)
            ->orderBy('name')
            ->get();

        $nilai = NilaiBulanan::where([
                'pembelajaran_id' => $pembelajaran->id,
                'bulan'           => $bulan,
                'semester'        => $semester,
                'tahun'           => $tahun,
            ])
            ->get()
            ->keyBy('siswa_id');

        return view('pages.nilaibulanan.show', compact(
            'pembelajaran',
            'siswa',
            'nilai',
            'bulan',
            'semester',
            'tahun'
        ));
    }

    /**
     * Simpan / update nilai bulanan
     */
    public function update(Request $request, Pembelajaran $pembelajaran)
    {
        $validator = Validator::make($request->all(), [
            'bulan'       => 'required|integer|between:1,12',
            'semester'    => 'required|integer|in:1,2',
            'tahun'       => 'required|integer',
            'siswa_id.*'  => 'required|exists:siswas,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'failed' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();

        try {
            foreach ($request->siswa_id as $siswaId) {

                $nilaiField = "nilai-$siswaId";

                if (!filled($request->$nilaiField)) {
                    continue;
                }

                NilaiBulanan::updateOrCreate(
                    [
                        'siswa_id'        => $siswaId,
                        'pembelajaran_id'=> $pembelajaran->id,
                        'bulan'           => $request->bulan,
                        'semester'        => $request->semester,
                        'tahun'           => $request->tahun,
                    ],
                    [
                        'nilai'               => $request->$nilaiField,
                        'capaian_tp_optimal'  => $request->capaian_tp_optimal[$siswaId] ?? null,
                        'capaian_tp_kurang'   => $request->capaian_tp_kurang[$siswaId] ?? null,
                        'deskripsi'           => $request->deskripsi[$siswaId] ?? null,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => 'Nilai bulanan berhasil disimpan'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'failed' => 'Terjadi kesalahan saat menyimpan data'
            ]);
        }
    }
}