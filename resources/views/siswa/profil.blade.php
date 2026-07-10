@extends('layouts.app')
@section('title','Profil Saya')
@section('sidebar-menu')
<div class="nav-section">Utama</div>
<li><a href="{{ route('siswa.dashboard') }}" class="nav-link {{ request()->routeIs('siswa.dashboard')?'active':'' }}">
  <i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
<div class="nav-section">Absensi</div>
<li><a href="{{ route('siswa.riwayat') }}" class="nav-link {{ request()->routeIs('siswa.riwayat')?'active':'' }}">
  <i class="bi bi-calendar3 me-2"></i>Riwayat Absensi</a></li>
<li><a href="{{ route('siswa.profil') }}" class="nav-link {{ request()->routeIs('siswa.profil')?'active':'' }}">
  <i class="bi bi-person me-2"></i>Profil Saya</a></li>
@endsection

@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-person me-2 text-primary"></i>Profil Saya</h4>

<div class="card" style="max-width:460px">
  <div class="card-body">
    <div class="text-center mb-4">
      <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3"
        style="width:80px;height:80px;font-size:2.5rem">
        <i class="bi bi-person-fill"></i>
      </div>
      <h5 class="fw-bold mb-0">{{ $siswa->nama }}</h5>
      <small class="text-muted">Siswa</small>
    </div>

    <table class="table table-borderless mb-0">
      <tr>
        <th class="text-muted small" style="width:120px">Nama Lengkap</th>
        <td><strong>{{ $siswa->nama }}</strong></td>
      </tr>
      <tr>
        <th class="text-muted small">NIS</th>
        <td><code>{{ $siswa->nis }}</code></td>
      </tr>
      <tr>
        <th class="text-muted small">Kelas</th>
        <td><span class="badge bg-info text-dark">{{ $siswa->kelas->nama_kelas }}</span></td>
      </tr>
      <tr>
        <th class="text-muted small">Jurusan</th>
        <td>
          <span class="badge bg-primary me-1">{{ $siswa->kelas->jurusan->kode }}</span>
          {{ $siswa->kelas->jurusan->nama }}
        </td>
      </tr>
      <tr>
        <th class="text-muted small">Username</th>
        <td>{{ $siswa->user->username }}</td>
      </tr>
    </table>
  </div>
</div>
@endsection
