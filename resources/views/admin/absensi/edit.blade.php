@extends('layouts.app')
@section('title','Edit Absensi')
@section('sidebar-menu') @include('layouts.admin-sidebar') @endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-primary"></i>Edit Absensi</h4>
  <a href="{{ route('admin.data-jurusan') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card" style="max-width:480px">
  <div class="card-body">
    <div class="alert alert-info small mb-3 py-2">
      <strong>Siswa:</strong> {{ $absensi->siswa->nama }} &nbsp;|&nbsp;
      <strong>Kelas:</strong> {{ $absensi->kelas->nama_kelas }} ({{ $absensi->kelas->jurusan->kode }}) &nbsp;|&nbsp;
      <strong>Tanggal:</strong> {{ $absensi->tanggal->format('d/m/Y') }}
    </div>
    <form action="{{ route('admin.absensi.update',$absensi->id_absensi) }}" method="POST">@csrf @method('PUT')
      <div class="mb-3">
        <label class="form-label fw-semibold small">Status Kehadiran *</label>
        <select name="status" class="form-select" required>
          @foreach(['hadir','izin','sakit','alpha'] as $s)
          <option value="{{ $s }}" {{ $absensi->status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
          @endforeach
        </select>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold small">Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="3" placeholder="Opsional...">{{ old('keterangan',$absensi->keterangan) }}</textarea>
      </div>
      <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
    </form>
  </div>
</div>
@endsection
