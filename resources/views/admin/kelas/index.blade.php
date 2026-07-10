@extends('layouts.app')
@section('title','Kelola Kelas')
@section('sidebar-menu') @include('layouts.admin-sidebar') @endsection

@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-building me-2 text-primary"></i>Kelola Kelas</h4>

<div class="row g-4">
  {{-- FORM TAMBAH KELAS BARU --}}
  <div class="col-md-4">
    <div class="card">
      <div class="card-header bg-primary text-white fw-semibold">
        <i class="bi bi-plus-circle me-2"></i>Tambah Kelas Baru
      </div>
      <div class="card-body">
        <p class="text-muted small mb-3">Tambah kelas baru pada jurusan yang sudah ada (RPL, TKJ, MM, AK, TKR, TSM).</p>
        <form action="{{ route('admin.kelas.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-semibold small">Jurusan <span class="text-danger">*</span></label>
            <select name="id_jurusan" class="form-select @error('id_jurusan') is-invalid @enderror" required>
              <option value="">-- Pilih Jurusan --</option>
              @foreach($jurusan as $j)
              <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan')==$j->id_jurusan ? 'selected':'' }}>
                {{ $j->kode }} — {{ $j->nama }}
              </option>
              @endforeach
            </select>
            @error('id_jurusan')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold small">Tingkat <span class="text-danger">*</span></label>
            <select name="tingkat" class="form-select" required>
              <option value="">-- Pilih --</option>
              <option value="X"   {{ old('tingkat')=='X'  ?'selected':'' }}>X (Sepuluh)</option>
              <option value="XI"  {{ old('tingkat')=='XI' ?'selected':'' }}>XI (Sebelas)</option>
              <option value="XII" {{ old('tingkat')=='XII'?'selected':'' }}>XII (Dua Belas)</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold small">Nama Kelas <span class="text-danger">*</span></label>
            <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}"
              class="form-control @error('nama_kelas') is-invalid @enderror"
              placeholder="Contoh: XI RPL 1">
            @error('nama_kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-save me-1"></i>Simpan Kelas Baru
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- TABEL SEMUA KELAS --}}
  <div class="col-md-8">
    <div class="card">
      <div class="card-header bg-white fw-semibold border-0 pt-3">
        <i class="bi bi-list-ul me-2 text-primary"></i>Semua Kelas ({{ $kelas->count() }} kelas)
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>Nama Kelas</th>
              <th>Jurusan</th>
              <th class="text-center">Tingkat</th>
              <th class="text-center">Siswa</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($kelas as $k)
            <tr>
              <td><strong>{{ $k->nama_kelas }}</strong></td>
              <td>
                <span class="badge bg-primary me-1">{{ $k->jurusan->kode }}</span>
                <small class="text-muted">{{ $k->jurusan->nama }}</small>
              </td>
              <td class="text-center"><span class="badge bg-secondary">{{ $k->tingkat }}</span></td>
              <td class="text-center">
                <span class="badge bg-light text-dark">{{ $k->siswa_count }} siswa</span>
              </td>
              <td class="text-center">
                <form action="{{ route('admin.kelas.destroy', $k->id_kelas) }}" method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Hapus kelas {{ $k->nama_kelas }}?\nSemua siswa di kelas ini juga akan terhapus!')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada kelas</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
