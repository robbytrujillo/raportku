<?php

namespace App\Http\Controllers;

use App\Models\Pembelajaran;
use App\Models\TujuanPembelajaranBulanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TujuanPembelajaranBulananController extends Controller
{
    //
    public function index(Request $request)
  {
    $data = Pembelajaran::latest();

    if ($request->ajax()) {

      if ($request->mapel_id) $data->where('mapel_id', $request->mapel_id);
      if ($request->kelas_id) $data->where('kelas_id', $request->kelas_id);
      if ($request->guru_id) $data->where('guru_id', $request->guru_id);

      return DataTables::of($data->with('kelas','guru','mapel'))
        ->addIndexColumn()
        ->editColumn('kelas.name', fn($data) => $data->kelas->name)
        ->editColumn('mapel.name', fn($data) => $data->mapel->name)
        ->editColumn('guru.name', fn($data) => $data->guru_pengampu())
        ->addColumn('aksi', function($data){
            return view('pages.tujuanpembelajaranbulanan._aksi')->with('data', $data);})
        ->make(true);
    }

    return view('pages.tujuanpembelajaranbulanan.index',[
      'pembelajaran' => $data,
      'kelas' => Kelas::get(),
      'mapel' => Mapel::get(),
      'guru' => Guru::get(),
    ]);
  }

  public function store(Request $request)
  {
    try {
      DB::beginTransaction();
        foreach ($request->keterangan as $i => $keterangan) {
          if (filled($request->keterangan[$i])) {
            TujuanPembelajaranBulanan::create([
              'pembelajaran_id' => $request->pembelajaran_id,
              'keterangan' => $request->keterangan[$i],
            ]);
          }
        }
      DB::commit();
      return response()->json(['success' => 'Berhasil ditambahkan!', 'req' => $request->all()]);
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json(['failed' => 'Terjadi kesalahan!']);
    }
  }

  public function show(Pembelajaran $tujuanpembelajaranbulanan, Request $request)
  {
    $user = Auth::user();
    if (!($user->isGuruMapel() && ($user->guru->id == $tujuanpembelajaranbulanan->guru_id ))) {
      abort(403);
    }

    $pembelajaran = $tujuanpembelajaranbulanan;
    $data = TujuanPembelajaranBulanan::where('pembelajaran_id', $pembelajaran->id);

    if ($request->ajax()) {

      return DataTables::of($data->with('pembelajaran'))
        ->addIndexColumn()
        ->editColumn('keterangan', function($data){
            return view('pages.tujuanpembelajaranbulanan.kelola.input._keterangan')->with('data', $data);})
        ->addColumn('aksi', function($data){
            return view('pages.tujuanpembelajaranbulanan.kelola.input._hapus')->with('data', $data);})
        ->make(true);
    }

    return view('pages.tujuanpembelajaranbulanan.kelola.show',[
      'tujuanpembelajaranbulanan' => $data,
      'pembelajaran' => $pembelajaran,
    ]);
  }

  public function update(Pembelajaran $tujuanpembelajaranbulanan, Request $request)
  {
    $pembelajaran = $tujuanpembelajaran;
    try {
      DB::beginTransaction();
        foreach ($request->id as $i => $id) {
          TujuanPembelajaranBulanan::find($id)->update([
            'keterangan' => $request->keterangan[$i],
          ]);
        }
      DB::commit();
      return response()->json(['success' => 'Berhasil diperbarui!']);
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json(['failed' => 'Terjadi kesalahan!']);
    }
  }

  public function delete(TujuanPembelajaranBulanan $tujuanpembelajaran)
  {
    try {
      DB::beginTransaction();
        $tujuanpembelajaranbulanan->delete();
      DB::commit();
      return response()->json(['success' => 'Berhasil dihapus!']);
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json(['failed' => 'Terjadi kesalahan!']);
    }
  }
}