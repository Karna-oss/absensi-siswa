@extends('layouts.app')
@section('title','Data Siswa')
@section('sidebar-menu') @include('layouts.admin-sidebar') @endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i>Data Siswa</h4>
  <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Siswa</a>
</div>
<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label small fw-semibold mb-1">Jurusan</label>
        <select name="id_jurusan" class="form-select form-select-sm">
          <option value="">Semua Jurusan</option>
          @foreach($jurusan as $j)
          <option value="{{ $j->id_jurusan }}" {{ request('id_jurusan')==$j->id_jurusan?'selected':'' }}>{{ $j->kode }} - {{ $j->nama }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold mb-1">Kelas</label>
        <select name="id_kelas" class="form-select form-select-sm">
          <option value="">Semua Kelas</option>
          @foreach($kelas as $k)
          <option value="{{ $k->id_kelas }}" {{ request('id_kelas')==$k->id_kelas?'selected':'' }}>{{ $k->nama_kelas }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><button class="btn btn-sm btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
    </form>
  </div>
</div>
<div class="card">
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead class="table-light"><tr><th>No</th><th>Nama</th><th>NIS</th><th>Kelas</th><th>Jurusan</th><th>Aksi</th></tr></thead>
      <tbody>
        @forelse($siswa as $i => $s)
        <tr>
          <td>{{ $siswa->firstItem()+$i }}</td>
          <td><strong>{{ $s->nama }}</strong></td>
          <td><code>{{ $s->nis }}</code></td>
          <td><span class="badge bg-info text-dark">{{ $s->kelas->nama_kelas }}</span></td>
          <td><span class="badge bg-primary">{{ $s->kelas->jurusan->kode }}</span></td>
          <td>
            <a href="{{ route('admin.siswa.edit',$s->id_siswa) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('admin.siswa.destroy',$s->id_siswa) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus siswa ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data siswa</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">{{ $siswa->links() }}</div>
</div>
@endsection
