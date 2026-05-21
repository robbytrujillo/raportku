<!DOCTYPE html>
<html>

@php
  use Carbon\Carbon;
  use Illuminate\Support\Str; 
@endphp

<head>
  <meta charset="utf-8" />
  <title>LAPORAN HASIL BELAJAR | {{$siswa->name}} ({{$siswa->nis}})</title>

  <style>
    body {
      font-family: "Times New Roman", serif;
      font-size: 12px;
    }

    /* =====================
       KOP SURAT
    ===================== */
    .kop-surat {
      text-align: center;
      margin-bottom: 25px; /* PENTING: jarak ke konten */
    }

    .kop-surat table {
      width: 100%;
      border-collapse: collapse;
    }

    .kop-surat .logo {
      width: 80px;
    }

    .kop-text-1 {
      font-size: 16px;
      font-weight: bold;
      letter-spacing: 1px;
      text-align: center;
    }

    .kop-text-2 {
      font-size: 20px;
      font-weight: bold;
      text-align: center;
    }

    .kop-text-3 {
      font-size: 11px;
      margin-top: 4px;
      line-height: 1.4;
      text-align: center;
    }

    .kop-line {
      border-top: 2px solid #000;
      margin-top: 8px;
    }

    /* =====================
       HEADER BIODATA
    ===================== */
    .header {
      margin-top: 20px; /* JARAK DARI KOP */
      margin-bottom: 15px;
    }

    .header table {
      width: 100%;
      border-collapse: collapse;
    }

    .header td {
      padding: 3px 5px;
      vertical-align: top;
      margin-top: 10px;
    }

    /* =====================
       TABEL NILAI
    ===================== */
    table {
      width: 100%;
      border-collapse: collapse;
    }

    .heading td {
      border: 1px solid #000;
      font-weight: bold;
      text-align: center;
      padding: 6px;
    }

    .nilai td {
      border: 1px solid #000;
      padding: 6px;
    }

    .center {
      text-align: center;
    }

    .footer {
      margin-top: 10px;
      font-size: 11px;
    }

    .page-break {
      page-break-after: always;
    }

    .invoice-box {
      margin-top: 10px;
    }

  </style>
</head>

<body>

<!-- ===================== KOP SURAT ===================== -->
<div class="kop-surat">
  <table>
    <tr>
      <td width="15%" style="text-align:center; vertical-align: middle;">
        <img src="{{ str_replace('\\', '/', public_path('img/logosekolah.png')) }}" class="logo">
        {{--  <img src="{{ asset('img/ihbs-logo.png') }}" class="logo">  --}}

        {{--  <img src="data:image/png;base64,{{ $logoBase64 }}" width="100">  --}}
            <img src="{{ public_path('img/ihbs-logo.png') }}" width="80">
      </td>
      <td width="85%">
        <div class="kop-text-1">YAYASAN DAKWAH ISLAM CAHAYA ILMU</div>
        <div class="kop-text-2">SMA IBNU HAJAR BOARDING SCHOOL</div>
        <div class="kop-text-3">
          Jl. Bungur II No. 77 RT.001 RW.007 Harjamukti Cimanggis Depok Jawa Barat<br>
          Telp/Fax: (021) 87750036 | Website: https://ihbs.sch.id | Email: smaihbs@gmail.com
        </div>
      </td>
    </tr>
  </table>
  <div class="kop-line"></div>
</div>
<!-- ===================== END KOP ===================== -->


<div class="invoice-box">

  <!-- ===================== HEADER ===================== -->
  <div class="header">
    <table>
      <tr>
        <td width="20%">Nama</td>
        <td width="30%">: {{$siswa->name}}</td>
        <td width="20%">Kelas</td>
        <td width="30%">: {{$siswa->kelas->name}}</td>
      </tr>
      <tr>
        <td>NIS / NISN</td>
        <td>: {{$siswa->nis}} / {{$siswa->nisn}}</td>
        <td>Fase</td>
        {{--  <td>: {{$siswa->kelas->tingkat->fase->name}}</td>  --}}
        <td>: {{ optional($siswa->kelas->tingkat->fase)->name ?? '-' }}</td>
      </tr>
      <tr>
        <td>Nama Sekolah</td>
        <td>: {{$sekolah->name}}</td>
        <td>Semester</td>
        {{--  <td>: {{ $siswa->kelas->tapel->semester == 1 ? 'Ganjil' : 'Genap' }}</td>  --}}
        <td>: {{ optional($siswa->kelas->tapel)->semester == 1 ? 'Ganjil' : 'Genap' }}</td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>: {{$siswa->kelas->tapel->tempat ?? 'Kota'}}</td>
        <td>Tahun Pelajaran</td>
        {{--  <td>: {{$siswa->kelas->tapel->tahun_pelajaran}}</td>  --}}
        <td>: {{ optional($siswa->kelas->tapel)->tahun_pelajaran ?? '-' }}</td>
      </tr>
    </table>
  </div>
  <!-- ===================== END HEADER ===================== -->


  <!-- ===================== JUDUL ===================== -->
  <div style="text-align:center; margin-bottom:10px;">
    <strong>
      LAPORAN HASIL BELAJAR<br>
      {{ Str::upper(now()->translatedFormat('F Y')) }}
    </strong>
  </div>

  <!-- ===================== TABEL NILAI ===================== -->
  <table>
    <tr class="heading">
      <td width="5%">No</td>
      <td width="30%">Mata Pelajaran</td>
      <td width="10%">Nilai</td>
      <td>Capaian Kompetensi</td>
    </tr>

    @foreach ($pembelajaran as $i => $pemb)
      <tr class="nilai">
        <td class="center">{{$loop->iteration}}</td>
        <td>{{$pemb->mapel->name}}</td>
        <td class="center">
          {{ optional($siswa->nilaiAkhir->where('pembelajaran_id',$pemb->id)->first())->nilai ?? '-' }}
        </td>
        <td>
          {{ optional($siswa->nilaiAkhir->where('pembelajaran_id',$pemb->id)->first())->deskripsi_capaian_tinggi }}
          <br>
          {{ optional($siswa->nilaiAkhir->where('pembelajaran_id',$pemb->id)->first())->deskripsi_capaian_rendah }}
        </td>
      </tr>
    @endforeach
  </table>

  <div class="footer">
    <i>{{$siswa->kelas->name}} | {{$siswa->name}} | {{$siswa->nis}}</i>
    <span style="float:right;">Halaman 1</span>
  </div>

</div>

</body>
</html>
