@extends('layouts.app')
@section('title','Data Guru')
@section('sidebar-menu') @include('layouts.admin-sidebar') @endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold mb-0"><i class="bi bi-person-badge me-2 text-primary"></i>Data Guru</h4>
  <a href="{{ route('admin.guru.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Guru</a>
</div>
<div class="card">
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead class="table-light"><tr><th>No</th><th>Nama Guru</th><th>NIP</th><th>Username</th><th>Aksi</th></tr></thead>
      <tbody>
        @forelse($guru as $i => $g)
        <tr>
          <td>{{ $guru->firstItem()+$i }}</td>
          <td><strong>{{ $g->nama }}</strong></td>
          <td><code>{{ $g->nip ?? '-' }}</code></td>
          <td>{{ $g->user->username }}</td>
          <td>
            <a href="{{ route('admin.guru.edit',$g->id_guru) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('admin.guru.destroy',$g->id_guru) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus guru ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data guru</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">{{ $guru->links() }}</div>
</div>
@endsection
