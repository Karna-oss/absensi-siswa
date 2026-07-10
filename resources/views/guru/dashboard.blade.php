@extends('layouts.app')
@section('title','Dashboard Guru')
@section('sidebar-menu') @include('layouts.guru-sidebar') @endsection
@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Guru</h4>
<div class="card mb-4">
  <div class="card-body d-flex align-items-center gap-3">
    <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center" style="width:60px;height:60px;font-size:1.8rem">
      <i class="bi bi-person-badge-fill"></i>
    </div>
    <div>
      <h5 class="fw-bold mb-0">{{ $guru->nama }}</h5>
      <small class="text-muted">NIP: {{ $guru->nip ?? '-' }} | {{ auth()->user()->username }}</small>
    </div>
  </div>
</div>
<div class="row g-3">
  <div class="col-md-4">
    <div class="stat-card" style="background:linear-gradient(135deg,#16a34a,#22c55e)">
      <p class="opacity-75 small mb-1">Absensi Hari Ini</p>
      <h2 class="fw-bold mb-2">{{ $absensiHariIni }}</h2>
      <a href="{{ route('guru.input') }}" class="btn btn-sm btn-light"><i class="bi bi-plus me-1"></i>Input Absensi</a>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6)">
      <p class="opacity-75 small mb-1">Total Input Absensi</p>
      <h2 class="fw-bold mb-2">{{ $totalInput }}</h2>
      <a href="{{ route('guru.data-jurusan') }}" class="btn btn-sm btn-light"><i class="bi bi-eye me-1"></i>Lihat Data</a>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6)">
      <p class="opacity-75 small mb-1">Rekap Semua Jurusan</p>
      <i class="bi bi-diagram-3-fill fs-2 mb-2 d-block opacity-75"></i>
      <a href="{{ route('guru.data-jurusan') }}" class="btn btn-sm btn-light"><i class="bi bi-diagram-3 me-1"></i>Lihat Jurusan</a>
    </div>
  </div>
</div>
@endsection
