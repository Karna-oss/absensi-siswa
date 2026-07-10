@extends('layouts.app')
@section('title','Riwayat Absensi')
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
<h4 class="fw-bold mb-4"><i class="bi bi-calendar3 me-2 text-primary"></i>Riwayat Absensi Saya</h4>

{{-- FILTER --}}
<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label small fw-semibold mb-1">Filter Bulan</label>
        <input type="month" name="bulan" class="form-control form-control-sm" value="{{ request('bulan') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
        <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
        <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
      </div>
      <div class="col-md-2">
        <button class="btn btn-sm btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
      </div>
      <div class="col-md-1">
        <a href="{{ route('siswa.riwayat') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
      </div>
    </form>
  </div>
</div>

{{-- STATISTIK FILTER --}}
<div class="row g-2 mb-3">
  @foreach(['hadir'=>'success','izin'=>'primary','sakit'=>'warning','alpha'=>'danger'] as $s => $c)
  <div class="col-6 col-md-3">
    <div class="card text-center py-2 border-{{ $c }}">
      <span class="fw-bold text-{{ $c }} fs-5">{{ $stats[$s] ?? 0 }}</span>
      <small class="text-muted">{{ ucfirst($s) }}</small>
    </div>
  </div>
  @endforeach
</div>

{{-- TABEL --}}
<div class="card">
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>Tanggal</th>
          <th class="text-center">Status</th>
          <th>Dicatat Oleh</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
        @forelse($absensi as $a)
        <tr>
          <td>{{ $a->tanggal->isoFormat('dddd, D MMMM Y') }}</td>
          <td class="text-center">
            <span class="badge badge-{{ $a->status }} px-2 py-1 rounded-pill">
              {{ ucfirst($a->status) }}
            </span>
          </td>
          <td>{{ optional($a->guru)->nama ?? '-' }}</td>
          <td class="text-muted small">{{ $a->keterangan ?? '-' }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="text-center text-muted py-4">
            <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
            Tidak ada data pada periode ini
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">{{ $absensi->links() }}</div>
</div>
@endsection
