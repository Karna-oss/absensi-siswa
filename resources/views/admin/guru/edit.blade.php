@extends('layouts.app')
@section('title','Edit Guru')
@section('sidebar-menu') @include('layouts.admin-sidebar') @endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-primary"></i>Edit Guru</h4>
  <a href="{{ route('admin.guru') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card" style="max-width:560px">
  <div class="card-body">
    <form action="{{ route('admin.guru.update',$guru->id_guru) }}" method="POST">@csrf @method('PUT')
      <div class="mb-3">
        <label class="form-label fw-semibold small">Nama Lengkap *</label>
        <input type="text" name="nama" value="{{ old('nama',$guru->nama) }}" class="form-control @error('nama') is-invalid @enderror">
        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold small">NIP</label>
        <input type="text" name="nip" value="{{ old('nip',$guru->nip) }}" class="form-control @error('nip') is-invalid @enderror">
        @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold small text-muted">Username (tidak bisa diubah)</label>
        <input type="text" class="form-control bg-light" value="{{ $guru->user->username }}" disabled>
      </div>
      <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update Data</button>
    </form>
  </div>
</div>
@endsection
