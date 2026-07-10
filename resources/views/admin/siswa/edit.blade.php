@extends('layouts.app')
@section('title','Edit Siswa')
@section('sidebar-menu') @include('layouts.admin-sidebar') @endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-primary"></i>Edit Siswa</h4>
  <a href="{{ route('admin.siswa') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card" style="max-width:560px">
  <div class="card-body">
    <form action="{{ route('admin.siswa.update',$siswa->id_siswa) }}" method="POST">@csrf @method('PUT')
      <div class="mb-3">
        <label class="form-label fw-semibold small">Nama Lengkap *</label>
        <input type="text" name="nama" value="{{ old('nama',$siswa->nama) }}" class="form-control @error('nama') is-invalid @enderror">
        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold small">NIS *</label>
        <input type="text" name="nis" value="{{ old('nis',$siswa->nis) }}" class="form-control @error('nis') is-invalid @enderror">
        @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold small">Kelas *</label>
        <select name="id_kelas" class="form-select" required>
          @foreach($kelas as $k)
          <option value="{{ $k->id_kelas }}" {{ $siswa->id_kelas==$k->id_kelas?'selected':'' }}>
            {{ $k->nama_kelas }} ({{ $k->jurusan->kode }})
          </option>
          @endforeach
        </select>
      </div>
      <div class="mb-4">
        <label class="form-label small text-muted">Username (tidak bisa diubah)</label>
        <input class="form-control bg-light" value="{{ $siswa->user->username }}" disabled>
      </div>
      <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update Data</button>
    </form>
  </div>
</div>
@endsection
