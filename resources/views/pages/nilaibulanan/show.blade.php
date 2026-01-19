@extends('layouts.main')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <h4 class="fw-bold">
      Input Nilai Bulanan <br>
      <small>{{ $pembelajaran->mapel->name }} - {{ $pembelajaran->kelas->name }}</small>
    </h4>
  </div>
</div>

<section class="content">
<div class="container-fluid">

<form id="formNilaiBulanan">
@csrf
<input type="hidden" name="bulan" value="{{ $bulan }}">
<input type="hidden" name="semester" value="{{ $semester }}">
<input type="hidden" name="tahun" value="{{ $tahun }}">

<table class="table table-bordered table-sm">
<thead class="bg-dark text-white">
<tr>
  <th>No</th>
  <th>Nama Siswa</th>
  <th width="10%">Nilai</th>
  <th>Capaian TP Optimal</th>
  <th>Capaian TP Perlu Peningkatan</th>
  <th>Deskripsi</th>
</tr>
</thead>

<tbody>
@foreach ($siswa as $s)
<tr>
  <td>{{ $loop->iteration }}</td>
  <td>
    {{ $s->name }}
    <input type="hidden" name="siswa_id[]" value="{{ $s->id }}">
  </td>

  {{-- NILAI --}}
  <td>
    <input type="number"
      class="form-control form-control-sm"
      name="nilai-{{ $s->id }}"
      value="{{ $nilai[$s->id]->nilai ?? '' }}"
      min="0" max="100">
  </td>

  {{-- CAPAIAN OPTIMAL --}}
  <td>
    <textarea class="form-control form-control-sm"
      name="deskripsi_optimal[{{ $s->id }}]"
      rows="2">{{ $nilai[$s->id]->capaian_tp_optimal ?? '' }}</textarea>
  </td>

  {{-- CAPAIAN KURANG --}}
  <td>
    <textarea class="form-control form-control-sm"
      name="deskripsi_kurang[{{ $s->id }}]"
      rows="2">{{ $nilai[$s->id]->capaian_tp_kurang ?? '' }}</textarea>
  </td>

  {{-- DESKRIPSI --}}
  <td>
    <textarea class="form-control form-control-sm"
      name="deskripsi[{{ $s->id }}]"
      rows="2">{{ $nilai[$s->id]->deskripsi ?? '' }}</textarea>
  </td>
</tr>
@endforeach
</tbody>
</table>

<button class="btn btn-primary btn-sm">
  <i class="fas fa-save"></i> Simpan Nilai Bulanan
</button>

</form>

</div>
</section>
@endsection

@section('js')
<script>
$('#formNilaiBulanan').submit(function(e){
  e.preventDefault();

  $.ajax({
    url: "{{ route('nilaibulanan.update', $pembelajaran->id) }}",
    type: "POST",
    data: $(this).serialize(),
    success: function(res){
      alert(res.success ?? res.failed);
    },
    error: function(){
      alert('Terjadi kesalahan');
    }
  });
});
</script>
@endsection
