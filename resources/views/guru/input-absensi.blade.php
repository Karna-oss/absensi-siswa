@extends('layouts.app')
@section('title','Input Absensi')
@section('sidebar-menu') @include('layouts.guru-sidebar') @endsection
@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-calendar-check me-2 text-primary"></i>Input Absensi Siswa</h4>

<div class="card mb-4">
  <div class="card-header bg-primary text-white fw-semibold">
    <i class="bi bi-funnel me-2"></i>Step 1 — Pilih Jurusan, Kelas & Tanggal
  </div>
  <div class="card-body">
    <form method="GET" action="{{ route('guru.input') }}" class="row g-3 align-items-end">

      {{-- Pilih Jurusan (hanya untuk filter dropdown kelas, tidak dikirim ke server) --}}
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Jurusan</label>
        <select id="selJurusan" class="form-select">
          <option value="">-- Pilih Jurusan --</option>
          @foreach($jurusan as $j)
          <option value="{{ $j->id_jurusan }}"
            {{ request('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
            {{ $j->kode }} - {{ $j->nama }}
          </option>
          @endforeach
        </select>
      </div>

      {{-- Pilih Kelas (difilter JS berdasarkan jurusan) --}}
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Kelas</label>
        <select name="id_kelas" id="selKelas" class="form-select" required>
          <option value="">-- Pilih Kelas --</option>
          @foreach($kelas as $k)
          <option value="{{ $k->id_kelas }}"
            data-jurusan="{{ $k->id_jurusan }}"
            {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
            {{ $k->nama_kelas }}
          </option>
          @endforeach
        </select>
      </div>

      {{-- Tanggal --}}
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Tanggal</label>
        <input type="date" name="tanggal" class="form-control"
          value="{{ request('tanggal', date('Y-m-d')) }}" required>
      </div>

      {{-- Tombol --}}
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-search me-1"></i>Tampilkan Siswa
        </button>
      </div>

    </form>
  </div>
</div>

{{-- STEP 2: Tabel absensi siswa --}}
@if($siswaList->count() > 0)
<form action="{{ route('guru.simpan') }}" method="POST">
  @csrf
  <input type="hidden" name="id_kelas" value="{{ request('id_kelas') }}">
  <input type="hidden" name="tanggal"  value="{{ request('tanggal') }}">

  <div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
      <span><i class="bi bi-people me-2"></i>Step 2 — Isi Status Kehadiran ({{ $siswaList->count() }} siswa)</span>
      <div class="d-flex gap-2">
        <button type="button" id="btnHadir" class="btn btn-sm btn-light">
          <i class="bi bi-check-all me-1"></i>Semua Hadir
        </button>
        <button type="button" id="btnReset" class="btn btn-sm btn-outline-light">
          <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
        </button>
      </div>
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:40px">No</th>
            <th>Nama Siswa</th>
            <th>NIS</th>
            <th class="text-center text-success">Hadir</th>
            <th class="text-center text-primary">Izin</th>
            <th class="text-center text-warning">Sakit</th>
            <th class="text-center text-danger">Alpha</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($siswaList as $i => $s)
          <tr>
            <td class="text-muted">{{ $i + 1 }}</td>
            <td><strong>{{ $s->nama }}</strong></td>
            <td><code class="small">{{ $s->nis }}</code></td>
            @foreach(['hadir','izin','sakit','alpha'] as $status)
            <td class="text-center">
              <input type="radio"
                name="absensi[{{ $s->id_siswa }}]"
                value="{{ $status }}"
                class="form-check-input"
                {{ ($absensiExisting[$s->id_siswa] ?? 'hadir') === $status ? 'checked' : '' }}>
            </td>
            @endforeach
            <td>
              <input type="text"
                name="keterangan[{{ $s->id_siswa }}]"
                class="form-control form-control-sm"
                placeholder="Opsional..."
                style="min-width:110px">
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center">
      <small class="text-muted">
        Kelas: <strong>{{ optional(\App\Models\Kelas::find(request('id_kelas')))->nama_kelas }}</strong> |
        Tanggal: <strong>{{ \Carbon\Carbon::parse(request('tanggal'))->isoFormat('D MMMM Y') }}</strong>
      </small>
      <button type="submit" class="btn btn-success">
        <i class="bi bi-save me-1"></i>Simpan Absensi
      </button>
    </div>
  </div>
</form>

@elseif(request('id_kelas'))
<div class="alert alert-warning">
  <i class="bi bi-exclamation-triangle me-2"></i>Tidak ada siswa di kelas ini.
</div>
@endif

@endsection

@push('scripts')
<script>
const selJurusan = document.getElementById('selJurusan');
const selKelas   = document.getElementById('selKelas');

// Semua option kelas (simpan semua sebelum difilter)
const semuaOpsiKelas = Array.from(selKelas.options);

function filterKelas() {
  const idJurusan = selJurusan.value;

  // Kosongkan dropdown kelas
  selKelas.innerHTML = '<option value="">-- Pilih Kelas --</option>';

  // Tambahkan kelas yang sesuai jurusan
  semuaOpsiKelas.forEach(opt => {
    if (!opt.dataset.jurusan) return; // skip option default
    if (!idJurusan || opt.dataset.jurusan === idJurusan) {
      selKelas.appendChild(opt.cloneNode(true));
    }
  });

  // Jika hanya ada 1 kelas, pilih otomatis
  if (selKelas.options.length === 2) {
    selKelas.selectedIndex = 1;
  }
}

// Jalankan filter saat jurusan berubah
selJurusan.addEventListener('change', filterKelas);

// Jalankan saat halaman load (untuk mempertahankan pilihan setelah submit)
window.addEventListener('DOMContentLoaded', function() {
  const savedJurusan = '{{ request('id_jurusan') }}';
  const savedKelas   = '{{ request('id_kelas') }}';

  if (savedJurusan) {
    selJurusan.value = savedJurusan;
    filterKelas();
    if (savedKelas) selKelas.value = savedKelas;
  }
});

// Tombol semua hadir
document.getElementById('btnHadir')?.addEventListener('click', () => {
  document.querySelectorAll('input[type=radio][value=hadir]').forEach(r => r.checked = true);
});

// Tombol reset
document.getElementById('btnReset')?.addEventListener('click', () => {
  document.querySelectorAll('input[type=radio]').forEach(r => r.checked = false);
});
</script>
@endpush
