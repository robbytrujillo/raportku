<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class SekolahController extends Controller
{
    public function index() {
      return view('pages.sekolah.index',[
        'sekolah' => Sekolah::first(),
      ]);
    }

    public function updateData(Request $request){
      $validasi = Validator::make($request->all(),[
        'name' => 'required',
        'nss' => 'nullable',
        'npsn' => 'nullable',
        'alamat' => 'nullable',
        'kodepos' => 'nullable',
        'telepon' => 'nullable',
        'email' => 'nullable|email',
        'website' => 'nullable',
        'namakepsek' => 'nullable',
        'nipkepsek' => 'nullable',
        'logo' => 'nullable',
      ]);

      if ($validasi->fails()) {
        return response()->json(['errors' => $validasi->errors()]);
      } else {
        Sekolah::first()->update($request->all());
        return response()->json(['success' => 'Data Sekolah berhasil diperbarui']);
      }
    }

    public function updateLogo(Request $request){
      $validasi = Validator::make($request->all(),[
        'old_logo' => 'required',
        'logo' => 'required|image',
      ]);

      if ($validasi->fails()) {
        return response()->json(['errors' => $validasi->errors()]);
      } else {
        $fileName = 'logo' . time() . '.' . $request->file('logo')->getClientOriginalExtension();

        try {
          DB::beginTransaction();
            $request->file('logo')->move('img', $fileName);
            Sekolah::first()->update(['logo' => $fileName]);
            if ($request->old_logo != 'logosekolah.png') File::delete(public_path('/img/' . $request->old_logo));
          DB::commit();
        } catch (\Throwable $th) {
          return response()->json(['failed' => 'Terjadi kesalahan!']);
          DB::rollBack();
        }
      }
      return response()->json(['success' => 'Logo Sekolah berhasil diperbarui']);
    }

    // ➕ TAMBAHAN
  public function updateTtd(Request $request){
    $validasi = Validator::make($request->all(),[
      'ttd_kepsek' => 'required|image|max:2048',
    ]);

    if ($validasi->fails()) {
      return response()->json(['errors' => $validasi->errors()]);
    } else {

      $fileName = 'ttd_' . time() . '.' . $request->file('ttd_kepsek')->getClientOriginalExtension();

      try {
        DB::beginTransaction();

        $request->file('ttd_kepsek')->move('img', $fileName);

        $sekolah = Sekolah::first();

        // hapus lama jika ada
        if ($sekolah->ttd_kepsek && File::exists(public_path('img/'.$sekolah->ttd_kepsek))) {
          File::delete(public_path('img/'.$sekolah->ttd_kepsek));
        }

        $sekolah->update([
          'ttd_kepsek' => $fileName
        ]);

        DB::commit();

      } catch (\Throwable $th) {
        DB::rollBack();
        return response()->json(['failed' => 'Terjadi kesalahan!']);
      }
    }

    return response()->json(['success' => 'TTD berhasil diupload']);
  }
}