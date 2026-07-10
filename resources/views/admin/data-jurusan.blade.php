@extends('layouts.app')
@section('title','Data Jurusan & Absensi')
@section('sidebar-menu') @include('layouts.admin-sidebar') @endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <h4 class="fw-bold mb-0"><i class="bi bi-diagram-3 me-2 text-primary"></i>Data Jurusan, Kelas & Absensi Siswa</h4>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.excel.template') }}" class="btn btn-success btn-sm">
      <i class="bi bi-file-earmark-arrow-down me-1"></i>Download Template
    </a>
  </div>
</div>

{{-- IMPORT CSV --}}
<div class="card mb-3 border-0" style="background:#f0fdf4;border:1px solid #86efac!important">
  <div class="card-body py-2">
    <form action="{{ route('admin.excel.import') }}" method="POST" enctype="multipart/form-data"
      class="row g-2 align-items-center">
      @csrf
      <div class="col-auto"><span class="fw-semibold small text-success"><i class="bi bi-upload me-1"></i>Import CSV:</span></div>
      <div class="col-md-4">
        <input type="file" name="file" class="form-control form-control-sm" accept=".csv,.txt" required>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-success btn-sm">Upload & Import</button>
      </div>
      <div class="col-auto">
        <small class="text-muted">Gunakan template Exl yang sudah didownload</small>
      </div>
    </form>
  </div>
</div>

{{-- FILTER --}}
<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label small fw-semibold mb-1">Tanggal</label>
        <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ $tanggal }}">
      </div>
      <div class="col-md-4">
        <label class="form-label small fw-semibold mb-1">Filter Jurusan</label>
        <select name="id_jurusan" class="form-select form-select-sm">
          <option value="">Semua Jurusan</option>
          @foreach($jurusanList as $j)
          <option value="{{ $j->id_jurusan }}" {{ request('id_jurusan')==$j->id_jurusan ? 'selected':'' }}>
            {{ $j->kode }} - {{ $j->nama }}
          </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary btn-sm w-100">
          <i class="bi bi-funnel me-1"></i>Tampilkan
        </button>
      </div>
      <div class="col-md-1">
        <a href="{{ route('admin.data-jurusan') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
      </div>
    </form>
  </div>
</div>

{{-- STATISTIK HARI INI --}}
<div class="row g-2 mb-3">
  @foreach([
    ['hadir','success','check-circle-fill'],
    ['izin','primary','calendar2-check'],
    ['sakit','warning','thermometer-half'],
    ['alpha','danger','x-circle-fill'],
    ['belum','secondary','dash-circle'],
  ] as [$k,$c,$ic])
  <div class="col">
    <div class="card text-center py-2 border-{{ $c }} border-2">
      <i class="bi bi-{{ $ic }} text-{{ $c }}"></i>
      <div class="fw-bold text-{{ $c }} fs-5">{{ $stats[$k] }}</div>
      <small class="text-muted">{{ ucfirst($k) }}</small>
    </div>
  </div>
  @endforeach
</div>

<p class="text-muted small mb-3">
  Menampilkan data absensi tanggal:
  <strong>{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}</strong>
  — <span class="badge badge-belum">Abu-abu = Belum Absen</span>
</p>

{{-- ══ JURUSAN > KELAS > SISWA ══ --}}
@forelse($semuaJurusan as $jurusan)
<div class="jurusan-card">

  {{-- Header Jurusan --}}
  <div class="jurusan-header" data-bs-toggle="collapse" data-bs-target="#jurusan-{{ $jurusan->id_jurusan }}">
    <div class="d-flex align-items-center gap-3">
      <span class="badge bg-white text-primary fw-bold fs-6">{{ $jurusan->kode }}</span>
      <div>
        <div class="fw-bold">{{ $jurusan->nama }}</div>
        <small class="opacity-75">{{ $jurusan->kelas->count() }} kelas &bull; {{ $jurusan->kelas->sum('siswa_count') }} siswa</small>
      </div>
    </div>
    <i class="bi bi-chevron-down"></i>
  </div>

  {{-- Kelas-kelas dalam jurusan ini --}}
  <div class="collapse show" id="jurusan-{{ $jurusan->id_jurusan }}">
    @forelse($jurusan->kelas as $kelas)
    <div class="kelas-block">

      {{-- Header Kelas --}}
      <div class="kelas-header">
        <i class="bi bi-building text-primary"></i>
        <span>{{ $kelas->nama_kelas }}</span>
        <span class="ms-2 badge bg-primary">{{ $kelas->tingkat }}</span>
        <span class="ms-auto badge bg-secondary">{{ $kelas->siswa_count }} siswa</span>

        {{-- Statistik kelas hari ini --}}
        @php
          $hdr = $kelas->siswa->filter(fn($s) => $s->absensi->isNotEmpty());
          $belumKelas = $kelas->siswa->filter(fn($s) => $s->absensi->isEmpty())->count();
        @endphp
        <span class="ms-2 badge badge-hadir">H:{{ $kelas->siswa->filter(fn($s) => optional($s->absensi->first())->status === 'hadir')->count() }}</span>
        <span class="badge badge-izin">I:{{ $kelas->siswa->filter(fn($s) => optional($s->absensi->first())->status === 'izin')->count() }}</span>
        <span class="badge badge-sakit">S:{{ $kelas->siswa->filter(fn($s) => optional($s->absensi->first())->status === 'sakit')->count() }}</span>
        <span class="badge badge-alpha">A:{{ $kelas->siswa->filter(fn($s) => optional($s->absensi->first())->status === 'alpha')->count() }}</span>
        <span class="badge badge-belum">?:{{ $belumKelas }}</span>
      </div>

      {{-- Tabel Siswa --}}
      @if($kelas->siswa->count() > 0)
      <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:40px">No</th>
              <th>Nama Siswa</th>
              <th>NIS</th>
              <th class="text-center">Status Absensi</th>
              <th>Keterangan</th>
              <th style="width:100px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($kelas->siswa as $idx => $siswa)
            @php $absensi = $siswa->absensi->first(); @endphp
            <tr>
              <td class="text-muted">{{ $idx + 1 }}</td>
              <td><strong>{{ $siswa->nama }}</strong></td>
              <td><code class="small">{{ $siswa->nis }}</code></td>
              <td class="text-center">
                @if($absensi)
                  <span class="badge badge-{{ $absensi->status }} px-2 py-1 rounded-pill">
                    {{ ucfirst($absensi->status) }}
                  </span>
                @else
                  <span class="badge badge-belum px-2 py-1 rounded-pill">
                    <i class="bi bi-dash me-1"></i>Belum Absen
                  </span>
                @endif
              </td>
              <td class="text-muted small">{{ $absensi?->keterangan ?? '-' }}</td>
              <td>
                @if($absensi)
                  <a href="{{ route('admin.absensi.edit', $absensi->id_absensi) }}"
                    class="btn btn-xs btn-outline-warning py-0 px-1">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="{{ route('admin.absensi.destroy', $absensi->id_absensi) }}"
                    method="POST" class="d-inline"
                    onsubmit="return confirm('Hapus absensi {{ $siswa->nama }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-xs btn-outline-danger py-0 px-1">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @else
        <div class="p-3 text-muted small text-center">Belum ada siswa di kelas ini</div>
      @endif

    </div>
    @empty
      <div class="p-3 text-muted small text-center">Belum ada kelas di jurusan ini</div>
    @endforelse
  </div>

</div>
@empty
<div class="alert alert-info">Belum ada data jurusan.</div>
@endforelse

<div class="mt-2 d-flex justify-content-end">
  <button onclick="window.print()" class="btn btn-sm btn-outline-dark">
    <i class="bi bi-printer me-1"></i>Cetak Halaman Ini
  </button>
</div>
@endsection

@push('styles')
<style>
.btn-xs { font-size: .75rem; }
@media print {
  .sidebar, .main > form, .card:first-child, .row.g-2.mb-3, button, a.btn { display: none !important; }
  .main { margin-left: 0 !important; }
  .jurusan-card { break-inside: avoid; }
}
</style>
@endpush
