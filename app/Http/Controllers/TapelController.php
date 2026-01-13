<?php

namespace App\Http\Controllers;

use App\Models\Tapel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TapelController extends Controller
{
    public function index() {
      return view('pages.tapel.index',[
        'tapel' => Tapel::get(),
      ]);
    }

    public function edit(Tapel $tapel) {
      return view('pages.tapel.edit',[
        'tapel' => $tapel,
        'tahun1' => Str::before($tapel->tahun_pelajaran, '/'),
        'tahun2' => Str::after($tapel->tahun_pelajaran, '/')
      ]);
    }

    public function update(Request $request, Tapel $tapel) {
      $request->validate([
        'tahun1' => 'required|numeric|digits:4',
        'tahun2' => 'required|numeric|digits:4',
        'semester' => 'required',
        'tempat' => 'nullable',
        'tanggal' => 'nullable|date',
      ]);

      if ((intval($request->tahun1) + 1) !== intval($request->tahun2)){
        return back()->withInput()->withFailed('Pengisian Tahun pelajaran harus sesuai ketentuan!');
      }

      $tapel->update([
        'tahun_pelajaran' => $request->tahun1 . '/' . $request->tahun2,
        'semester' => $request->semester,
        'tempat' => $request->tempat,
        'tanggal' => $request->tanggal,
      ]);

      return redirect(route('tapel.index'))->withSuccess('Data berhasil diperbarui!');
    }
}
