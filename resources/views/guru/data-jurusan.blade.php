@extends('layouts.app')
@section('title','Data Jurusan & Absensi')
@section('sidebar-menu') @include('layouts.guru-sidebar') @endsection
@section('content')
<h4 class="fw-bold mb-3"><i class="bi bi-diagram-3 me-2 text-primary"></i>Data Jurusan, Kelas & Absensi Siswa</h4>

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
          <option value="{{ $j->id_jurusan }}" {{ request('id_jurusan')==$j->id_jurusan?'selected':'' }}>
            {{ $j->kode }} - {{ $j->nama }}
          </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel me-1"></i>Tampilkan</button>
      </div>
      <div class="col-md-1">
        <a href="{{ route('guru.data-jurusan') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="row g-2 mb-3">
  @foreach([['hadir','success','check-circle-fill'],['izin','primary','calendar2-check'],['sakit','warning','thermometer-half'],['alpha','danger','x-circle-fill'],['belum','secondary','dash-circle']] as [$k,$c,$ic])
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
  Tanggal: <strong>{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}</strong>
  &nbsp;<span class="badge badge-belum">Abu-abu = Belum Absen</span>
</p>

@forelse($semuaJurusan as $jurusan)
<div class="jurusan-card">
  <div class="jurusan-header" data-bs-toggle="collapse" data-bs-target="#guru-j-{{ $jurusan->id_jurusan }}">
    <div class="d-flex align-items-center gap-3">
      <span class="badge bg-white text-primary fw-bold fs-6">{{ $jurusan->kode }}</span>
      <div>
        <div class="fw-bold">{{ $jurusan->nama }}</div>
        <small class="opacity-75">{{ $jurusan->kelas->count() }} kelas</small>
      </div>
    </div>
    <i class="bi bi-chevron-down"></i>
  </div>

  <div class="collapse show" id="guru-j-{{ $jurusan->id_jurusan }}">
    @forelse($jurusan->kelas as $kelas)
    <div class="kelas-block">
      <div class="kelas-header">
        <i class="bi bi-building text-primary"></i>
        <span>{{ $kelas->nama_kelas }}</span>
        <span class="ms-2 badge bg-primary">{{ $kelas->tingkat }}</span>
        <span class="ms-auto badge bg-secondary">{{ $kelas->siswa_count }} siswa</span>
        @php $belumKelas = $kelas->siswa->filter(fn($s) => $s->absensi->isEmpty())->count(); @endphp
        <span class="ms-2 badge badge-hadir">H:{{ $kelas->siswa->filter(fn($s) => optional($s->absensi->first())->status==='hadir')->count() }}</span>
        <span class="badge badge-izin">I:{{ $kelas->siswa->filter(fn($s) => optional($s->absensi->first())->status==='izin')->count() }}</span>
        <span class="badge badge-sakit">S:{{ $kelas->siswa->filter(fn($s) => optional($s->absensi->first())->status==='sakit')->count() }}</span>
        <span class="badge badge-alpha">A:{{ $kelas->siswa->filter(fn($s) => optional($s->absensi->first())->status==='alpha')->count() }}</span>
        <span class="badge badge-belum">?:{{ $belumKelas }}</span>
        <a href="{{ route('guru.input', ['id_jurusan'=>$jurusan->id_jurusan,'id_kelas'=>$kelas->id_kelas,'tanggal'=>$tanggal]) }}"
          class="btn btn-sm btn-outline-success ms-2" style="font-size:.75rem">
          <i class="bi bi-pencil-square me-1"></i>Input
        </a>
      </div>

      @if($kelas->siswa->count() > 0)
      <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
          <thead class="table-light">
            <tr><th style="width:40px">No</th><th>Nama Siswa</th><th>NIS</th><th class="text-center">Status Absensi</th><th>Keterangan</th></tr>
          </thead>
          <tbody>
            @foreach($kelas->siswa as $idx => $siswa)
            @php $absensi = $siswa->absensi->first(); @endphp
            <tr>
              <td class="text-muted">{{ $idx+1 }}</td>
              <td><strong>{{ $siswa->nama }}</strong></td>
              <td><code class="small">{{ $siswa->nis }}</code></td>
              <td class="text-center">
                @if($absensi)
                  <span class="badge badge-{{ $absensi->status }} px-2 py-1 rounded-pill">{{ ucfirst($absensi->status) }}</span>
                @else
                  <span class="badge badge-belum px-2 py-1 rounded-pill"><i class="bi bi-dash me-1"></i>Belum Absen</span>
                @endif
              </td>
              <td class="text-muted small">{{ $absensi?->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @else
        <div class="p-3 text-center text-muted small">Belum ada siswa di kelas ini</div>
      @endif
    </div>
    @empty
      <div class="p-3 text-center text-muted small">Belum ada kelas di jurusan ini</div>
    @endforelse
  </div>
</div>
@empty
<div class="alert alert-info">Belum ada data jurusan.</div>
@endforelse
@endsection
