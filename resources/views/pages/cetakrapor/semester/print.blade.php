<!DOCTYPE html>
<html>

@php
  use Carbon\Carbon;
  use Illuminate\Support\Str;
@endphp

<head>
  <meta charset="utf-8" />
  <title>LAPORAN HASIL BELAJAR | {{$siswa->name}} ({{$siswa->nis}})</title>
  {{--  <link href="./cetakraport/invoice_raport.css" rel="stylesheet">    --}}
  <style>
    {!! file_get_contents(public_path('cetakraport/invoice_raport.css')) !!}
  </style>

  <style>
body {
  font-family: "Times New Roman", serif;
  font-size: 12px;
}

.kop-text-1 {
  font-size: 16px;
  font-weight: bold;
}

.kop-text-2 {
  font-size: 22px; /* lebih besar */
  font-weight: bold; /* lebih tebal */
  letter-spacing: 1px; /* biar elegan */
  margin-bottom: 0px; /* JARAK KE BAWAH */
}

.kop-text-3 {
  font-size: 11px;
  line-height: 1.4; /* biar tidak terlalu rapat */
}
</style>

</head>

<body>

  <div class="invoice-box">

    {{-- HEADER --}}
    <div class="header">
      <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
          
          <td width="15%" style="text-align:center;">
            {{--  <img src="{{ public_path('img/ihbs-logo.png') }}" width="80">  --}}
            {{--  <img src="{{ asset('img/ihbs-logo.png') }}" width="80">  --}}
            @php
              $path = public_path('img/ihbs-logo.png');
              $type = pathinfo($path, PATHINFO_EXTENSION);
              $data = file_get_contents($path);
              $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            @endphp
          
            <img src="{{ $base64 }}" width="80">
          </td>
          <td width="85%" style="text-align:center;">
            <div class="kop-text-1">YAYASAN DAKWAH ISLAM CAHAYA ILMU</div>
            <div class="kop-text-2">SMA IBNU HAJAR BOARDING SCHOOL</div>
            <div class="kop-text-3">
              Jl. Bungur II No. 77 RT.001 RW.007 Harjamukti Cimanggis Depok Jawa Barat<br>
              Telp/Fax: (021) 87750036 &nbsp; Website: https://ihbs.sch.id &nbsp; Email: smaihbs@gmail.com
            </div>
          </td>
        </tr>
      </table>
    </div>
    {{-- END HEADER --}}

    {{-- CONTENT --}}
    <div class="content">

      <div class="" style="text-align: center">
        <h3><strong>LAPORAN BULANAN (PERIODE : {{ Str::upper(now()->format('F')) }}) <br>TAHUN PELAJARAN {{$siswa->kelas->tapel->tahun_pelajaran}}</strong></h3>
        {{--  <h3><strong><td>Tahun Pelajaran : {{$siswa->kelas->tapel->tahun_pelajaran}}</td></strong></h3>  --}}
        <table style="width: 40%; margin-left: 0;">
        <tr class="">
          <td>Nama Siswa</td>
          <td>: {{$siswa->name}} </td>
          
        </tr>
        <tr>
          <td>Kelas/Perminatan</td>
          <td>: {{$siswa->kelas->name}}</td>
        </tr>
        <tr>
          <td>Semester</td>
          <td>: {{ $siswa->kelas->tapel->semester == 1 ? 'Ganjil' : 'Genap'}}</td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td>
            {{--  {{ $siswa->kelas->tapel->semester == 1 ? '1 (Ganjil)' : '2 (Genap)'}}  --}}
            {{--  {{ $siswa->kelas->tapel->semester == 1 ? 'Ganjil' : 'Genap'}}  --}}
          </td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>

          {{--  <td>Alamat</td>
          <td>: {{$siswa->kelas->tapel->tempat ?? 'Kota'}}</td>
          <td>Tahun Pelajaran</td>
          <td>: {{$siswa->kelas->tapel->tahun_pelajaran}}</td>  --}}
        </tr>
      </table>
      </div>

      <table cellspacing="0">

      <tr class="heading">
        <td style="width: 8%">No</td>
        <td style="width: 30%">Mata Pelajaran</td>
        <td style="width: 10%">Angka</td>
        <td>Deskripsi</td>
      </tr>

      @if ($kelompokmapel->count() >= 1)

        @php
          $no = 0;
          $totalNilai = 0;
          $jumlahMapel = 0;
        @endphp

        @foreach ($kelompokmapel as $item)

          <tr class="nilai">
            <td colspan="4"><strong>{{ $item->name }}</strong></td>
          </tr>

          @php
            $mapelId = \App\Models\Mapel::where('kelompok_mapel_id', $item->id)->pluck('id');
          @endphp

          @foreach($pembelajaran->whereIn('mapel_id', $mapelId) as $pemb)

            @php
              $no++;

              $nilaiData = $siswa->nilaiAkhir
                ->where('pembelajaran_id', $pemb->id)
                ->first();

              $nilai = $nilaiData->nilai ?? null;

              if($nilai !== null){
                $totalNilai += $nilai;
                $jumlahMapel++;
              }
            @endphp

            <tr class="nilai">
              <td class="center">{{ $no }}</td>
              <td>{{ $pemb->mapel->name }}</td>
              <td class="center">{{ $nilai ?? '-' }}</td>
              <td>
                <span>{{ $nilaiData->deskripsi_capaian_tinggi ?? '' }}</span>
                <span style="display: flex;">
                  {{ $nilaiData->deskripsi_capaian_rendah ?? '' }}
                </span>
              </td>
            </tr>

          @endforeach

        @endforeach

        {{-- TOTAL --}}
        <tr class="nilai">
          <td colspan="2"><strong>Total Nilai</strong></td>
          <td class="center"><strong>{{ $totalNilai }}</strong></td>
          <td></td>
        </tr>

        {{-- RATA-RATA --}}
        <tr class="nilai">
          <td colspan="2"><strong>Rata-rata</strong></td>
          <td class="center">
            <strong>
              {{ $jumlahMapel > 0 ? number_format($totalNilai / $jumlahMapel, 2) : '-' }}
            </strong>
          </td>
          <td></td>
        </tr>

      @endif

    </table>
    </div>
    {{-- END CONTENT --}}

    {{-- FOOTER --}}
    {{--  <div class="footer" style="font-family: 'Times New Roman'; font-weight: bold;">
      <i>{{$siswa->kelas->name}} | {{$siswa->name}} | {{$siswa->nis}}</i> <b style="float: right;"><i>Halaman 1</i></b>
    </div>  --}}
    {{-- END FOOTER --}}

  {{--  </div>  --}}

  {{--  <div class="page-break"></div>  --}}

  {{--  <!-- Page 4 (Other) -->
  <div class="invoice-box">  --}}

    {{-- HEADER --}}
    {{--  <div class="header">
      <table>  --}}
        {{--  <tr>
          <td>Nama</td>
          <td>: {{$siswa->name}} </td>
          <td>Kelas</td>
          <td>: {{$siswa->kelas->name}}</td>
        </tr>
        <tr>
          <td>NIS/NISN</td>
          <td>: {{$siswa->nis}} / {{$siswa->nisn}} </td>
          <td>Fase</td>
          <td>: {{$siswa->kelas->tingkat->fase->name}}</td>
        </tr>
        <tr>
          <td>Nama Sekolah</td>
          <td>: {{$sekolah->name}}</td>
          <td>Semester</td>
          <td>:
            {{ $siswa->kelas->tapel->semester == 1 ? '1 (Ganjil)' : '2 (Genap)'}}
          </td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td>: {{$siswa->kelas->tapel->tempat ?? 'Kota'}}</td>
          <td>Tahun Pelajaran</td>
          <td>: {{$siswa->kelas->tapel->tahun_pelajaran}}</td>
        </tr>  --}}
      {{--  </table>
    </div>  --}}

    {{-- CONTENT --}}
    {{--  <div class="content">
      <table cellspacing="0">
        <!-- Ekskul -->
        <tr class="heading">
          <td style="width: 8%;">NO</td>
          <td style="width: 30%;">Kegiatan Ekstrakurikuler</td>
          <td style="width: 20%">Predikat</td>
          <td >Keterangan</td>
        </tr>
        @if ($anggotaEkskul->count() >= 1)
          @foreach ($anggotaEkskul as $a => $angg)
            <tr class="nilai">
              <td class="center">{{$loop->iteration}}</td>
              <td> {{$angg->ekskul->name }} </td>
              <td style="text-align: center;">
                @if ($angg->predikat == 'A')
                  Sangat Baik
                @elseif ($angg->predikat == 'B')
                  Baik
                @elseif ($angg->predikat == 'C')
                  Cukup
                @elseif ($angg->predikat == 'D')
                  Kurang
                @endif
              </td>
              <td>{{ $angg->deskripsi }}</td>
            </tr>
          @endforeach
        @else
          @foreach ([1,2] as $x)
            <tr class="nilai">
              <td class="center" style="padding: 15px;"></td>
              <td></td>
              <td style="text-align: center;"></td>
              <td></td>
            </tr>
          @endforeach
        @endif
        <!-- End Ekskul -->
      </table>

      <br><br>

      <table cellspacing="0" style="width: 50%">
        <!-- Ketidakhadiran  -->
          <tr class="nilai">
            <td style="border-right:0 ;">Sakit</td>
            <td style="border-left:0 ;">: {{$ketidakhadiran[0]->sakit ?? 0}} hari</td>
            <td class="false"></td>
          </tr>
          <tr class="nilai">
            <td style="border-right:0 ;">Izin</td>
            <td style="border-left:0 ;">: {{$ketidakhadiran[0]->izin ?? 0}} hari</td>
            <td class="false"></td>
          </tr>
          <tr class="nilai">
            <td style="border-right:0 ;">Tanpa Keterangan</td>
            <td style="border-left:0 ;">: {{$ketidakhadiran[0]->tk ?? 0}} hari</td>
            <td class="false"></td>
          </tr>
        <!-- End Ketidakhadiran  -->
      </table>

      <br>

      <table cellspacing="0">
        <!-- Catatan Wali Kelas -->
      <tr>
        <td colspan="4" style="height: 25px; padding-top: 5px"><strong>CATATAN WALI KELAS</strong></td>
      </tr>
      <tr class="sikap">
        <td colspan="4" class="description" style="height: 50px;">
          {{ $catatanwalas[0]->catatan ?? '-'  }}
        </td>
      </tr>
      <!-- End Catatan Wali Kelas -->

      @if ($siswa->kelas->tapel->semester == 2)
        <tr>
          <td colspan="4" style="height: 25px; padding-top: 15px"><strong>KEPUTUSAN</strong></td>
        </tr>
        <tr class="sikap">
          <td colspan="4" class="description" style="height: 50px;">
            Berdasarkan hasil pembelajaran yang dicapai, Peserta Didik ditetapkan:

            @if ($catatanwalas->count() >= 1)
              @if ($catatanwalas[0]->naik_tingkat == false)
                @if ($siswa->kelas->tingkat->angka == 6 || $siswa->kelas->tingkat->angka == 9 || $siswa->kelas->tingkat->angka == 12)
                  <b>TIDAK LULUS</b>
                @else
                  <b>TINGGAL DI KELAS {{ $siswa->kelas->tingkat->angka}}</b>
                @endif
              @else
                @if ($siswa->kelas->tingkat->angka == 6 || $siswa->kelas->tingkat->angka == 9 || $siswa->kelas->tingkat->angka == 12)
                  <b>LULUS</b>
                @else
                  <b>NAIK KE KELAS {{ intval($siswa->kelas->tingkat->angka) + 1 }}</b>
                @endif
              @endif
            @else
              @if ($siswa->kelas->tingkat->angka == 6 || $siswa->kelas->tingkat->angka == 9 || $siswa->kelas->tingkat->angka == 12)
                <b>LULUS</b>
              @else
                <b>NAIK KE KELAS {{ intval($siswa->kelas->tingkat->angka) + 1 }}</b>
              @endif
            @endif

          </td>
        </tr>
      @endif

      </table>

    </div>  --}}
    <br>
    <table style="width: 30%; border: 1px solid #000; margin-bottom: 20px;">
      <tr>
        <td style="padding: 4px;">
          <i>
            Kriteria : <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A : 92 - 100 <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B : 83 - 91 <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C : 76 - 82 <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;D : &lt; 76
          </i>
        </td>
      </tr>
    </table>

    <div style="padding-top:1rem;">
      <table>
        <tr>
          <td style="width: 35%;">
            Mengetahui <br>
            Kepala Sekolah, <br>
            {{--  <br><br><br><br>  --}}

            {{-- ✅ TTD KEPSEK --}}
            @if($sekolah->ttd_kepsek)
            @php
              $path = public_path('img/'.$sekolah->ttd_kepsek);
              $type = pathinfo($path, PATHINFO_EXTENSION);
              $data = file_get_contents($path);
              $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            @endphp
          
            <img src="{{ $base64 }}" width="80" height="70"> <br>

              {{--  <img src="{{ public_path('img/'.$sekolah->ttd_kepsek) }}" height="70"><br>  --}}
            @else
              <br><br><br>
            @endif

            <b><u>{{$sekolah->namakepsek}}</u></b><br>
            {{--  NIP. {{$sekolah->nipkepsek}}  --}}
          </td>
          <td style="width: 35%;"></td>

          {{--  <td style="width: 35%;">
            {{$siswa->kelas->tapel->tempat ?? 'Tempat'}}, {{ Carbon::createFromFormat('Y-m-d', Str::before($siswa->kelas->tapel->tanggal, ' '))->locale('id')->isoFormat('D MMMM YYYY') ?? 'Tanggal'}}, <br>
            Wali Kelas, <br><br><br><br>
            <b><u>{{$siswa->kelas->guru ? $siswa->kelas->guru->name : ''}}</u></b><br>
            NIP. {{$siswa->kelas->guru ? $siswa->kelas->guru->nip : ''}}
          </td>  --}}

          <td style="width: 35%;">
            {{$siswa->kelas->tapel->tempat ?? 'Tempat'}} 
            {{ Carbon::createFromFormat('Y-m-d', Str::before($siswa->kelas->tapel->tanggal, ' '))->locale('id')->isoFormat('D MMMM YYYY') ?? 'Tanggal'}}, <br>
            
            Wali Kelas, <br>

            {{-- ✅ TTD WALAS --}}
            @if($siswa->kelas->guru && $siswa->kelas->guru->ttd)
              @php
                $path = public_path('img/'.$siswa->kelas->guru->ttd);
                if(file_exists($path)){
                  $type = pathinfo($path, PATHINFO_EXTENSION);
                  $data = file_get_contents($path);
                  $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
              @endphp

              @if(isset($base64))
                <img src="{{ $base64 }}" width="80" height="70"><br>
              @else
                <br><br><br>
              @endif
            @else
              <br><br><br>
            @endif

            <b><u>{{$siswa->kelas->guru ? $siswa->kelas->guru->name : ''}}</u></b><br>
            {{--  NIP. {{$siswa->kelas->guru ? $siswa->kelas->guru->nip : ''}}  --}}
          </td>

        </tr>
        {{--  <tr>
          <td style="width: 30%;"></td>
          <td style="width: 35%;">
            Mengetahui <br>
            Kepala Sekolah, <br><br><br><br>
            <b><u>{{$sekolah->namakepsek}}</u></b><br>
            NIP. {{$sekolah->nipkepsek}}
          </td>
          <td style="width: 35%;"></td>
        </tr>  --}}
      </table>
    </div>

    {{-- FOOTER --}}
    <div class="footer" style="font-family: 'Times New Roman'; font-weight: bold;">
      <i>{{$siswa->kelas->name}} | {{$siswa->name}} | {{$siswa->nis}}</i> <b style="float: right;"><i></i></b>
    </div>

  </div>

</body>

</html>
