@extends('layouts.app')
@section('title','Tambah Guru')
@section('sidebar-menu') @include('layouts.admin-sidebar') @endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold mb-0"><i class="bi bi-person-plus me-2 text-primary"></i>Tambah Guru</h4>
  <a href="{{ route('admin.guru') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card" style="max-width:560px">
  <div class="card-body">
    <form action="{{ route('admin.guru.store') }}" method="POST">@csrf
      <div class="mb-3">
        <label class="form-label fw-semibold small">Nama Lengkap *</label>
        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Nama + gelar">
        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold small">NIP (opsional)</label>
        <input type="text" name="nip" value="{{ old('nip') }}" class="form-control @error('nip') is-invalid @enderror" placeholder="Nomor Induk Pegawai">
        @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <hr class="my-3">
      <p class="text-muted small mb-3"><i class="bi bi-info-circle me-1"></i>Akun login guru</p>
      <div class="mb-3">
        <label class="form-label fw-semibold small">Username *</label>
        <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror">
        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold small">Password * (min. 6 karakter)</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Guru</button>
    </form>
  </div>
</div>
@endsection
