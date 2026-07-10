@extends('layouts.app')
@section('title','Dashboard Admin')
@section('sidebar-menu') @include('layouts.admin-sidebar') @endsection

@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Admin</h4>

<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="stat-card" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6)">
      <div class="d-flex justify-content-between align-items-start">
        <div><p class="mb-1 opacity-75 small">Total Jurusan</p><h2 class="fw-bold mb-0">{{ $totalJurusan }}</h2></div>
        <i class="bi bi-diagram-3-fill fs-2 opacity-75"></i>
      </div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card" style="background:linear-gradient(135deg,#d97706,#f59e0b)">
      <div class="d-flex justify-content-between align-items-start">
        <div><p class="mb-1 opacity-75 small">Total Kelas</p><h2 class="fw-bold mb-0">{{ $totalKelas }}</h2></div>
        <i class="bi bi-building fs-2 opacity-75"></i>
      </div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6)">
      <div class="d-flex justify-content-between align-items-start">
        <div><p class="mb-1 opacity-75 small">Total Siswa</p><h2 class="fw-bold mb-0">{{ $totalSiswa }}</h2></div>
        <i class="bi bi-people-fill fs-2 opacity-75"></i>
      </div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card" style="background:linear-gradient(135deg,#16a34a,#22c55e)">
      <div class="d-flex justify-content-between align-items-start">
        <div><p class="mb-1 opacity-75 small">Absensi Hari Ini</p><h2 class="fw-bold mb-0">{{ $absensiHariIni }}</h2></div>
        <i class="bi bi-calendar-check-fill fs-2 opacity-75"></i>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-7">
    <div class="card h-100">
      <div class="card-header bg-white fw-semibold border-0 pt-3 pb-0">
        <i class="bi bi-diagram-3 me-2 text-primary"></i>Ringkasan Per Jurusan
      </div>
      <div class="card-body p-0">
        <table class="table mb-0">
          <thead class="table-light">
            <tr><th>Jurusan</th><th>Kode</th><th class="text-center">Kelas</th><th class="text-center">Siswa</th></tr>
          </thead>
          <tbody>
            @foreach($jurusanList as $j)
            <tr>
              <td><strong>{{ $j->nama }}</strong></td>
              <td><span class="badge bg-primary">{{ $j->kode }}</span></td>
              <td class="text-center">{{ $j->kelas_count }}</td>
              <td class="text-center">{{ $j->siswa_count }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card h-100">
      <div class="card-header bg-white fw-semibold border-0 pt-3 pb-0">
        <i class="bi bi-lightning-fill me-2 text-warning"></i>Akses Cepat
      </div>
      <div class="card-body d-flex flex-column gap-2">
        <a href="{{ route('admin.data-jurusan') }}" class="btn btn-primary btn-sm py-2">
          <i class="bi bi-diagram-3 me-2"></i>Lihat Data Jurusan & Absensi
        </a>
        <a href="{{ route('admin.excel.template') }}" class="btn btn-success btn-sm py-2">
          <i class="bi bi-file-earmark-arrow-down me-2"></i>Download Template CSV
        </a>
        <a href="{{ route('admin.siswa.create') }}" class="btn btn-outline-primary btn-sm py-2">
          <i class="bi bi-person-plus me-2"></i>Tambah Siswa
        </a>
        <a href="{{ route('admin.guru.create') }}" class="btn btn-outline-success btn-sm py-2">
          <i class="bi bi-person-badge me-2"></i>Tambah Guru
        </a>
        <a href="{{ route('admin.kelas') }}" class="btn btn-outline-warning btn-sm py-2">
          <i class="bi bi-building me-2"></i>Tambah Kelas Baru
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
