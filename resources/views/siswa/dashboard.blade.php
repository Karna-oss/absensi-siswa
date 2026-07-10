@extends('layouts.app')
@section('title','Dashboard Siswa')
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
<h4 class="fw-bold mb-4"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Siswa</h4>

<div class="card mb-4">
  <div class="card-body d-flex align-items-center gap-3">
    <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center"
      style="width:60px;height:60px;font-size:1.8rem">
      <i class="bi bi-person-fill"></i>
    </div>
    <div>
      <h5 class="fw-bold mb-0">{{ $siswa->nama }}</h5>
      <small class="text-muted">
        NIS: {{ $siswa->nis }} &nbsp;|&nbsp;
        Kelas: {{ $siswa->kelas->nama_kelas }} &nbsp;|&nbsp;
        Jurusan: <span class="badge bg-primary">{{ $siswa->kelas->jurusan->kode }}</span>
        {{ $siswa->kelas->jurusan->nama }}
      </small>
    </div>
  </div>
</div>

<h6 class="fw-bold text-muted mb-3 text-uppercase" style="font-size:.75rem;letter-spacing:.06em">
  Rekap Bulan Ini — {{ now()->isoFormat('MMMM Y') }}
</h6>

<div class="row g-3 mb-4">
  @foreach([
    ['hadir','success','check-circle-fill','Hadir'],
    ['izin', 'primary','calendar2-check',  'Izin'],
    ['sakit','warning','thermometer-half', 'Sakit'],
    ['alpha','danger', 'x-circle-fill',   'Alpha'],
  ] as [$key,$color,$icon,$label])
  <div class="col-6 col-md-3">
    <div class="card text-center border-{{ $color }} border-2">
      <div class="card-body py-3">
        <i class="bi bi-{{ $icon }} text-{{ $color }} fs-2 mb-1"></i>
        <h2 class="fw-bold text-{{ $color }} mb-0">{{ $bulanIni[$key] ?? 0 }}</h2>
        <small class="text-muted">Hari {{ $label }}</small>
      </div>
    </div>
  </div>
  @endforeach
</div>

<a href="{{ route('siswa.riwayat') }}" class="btn btn-outline-primary">
  <i class="bi bi-calendar3 me-1"></i>Lihat Riwayat Lengkap
</a>
@endsection
