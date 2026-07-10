<div class="nav-section">Utama</div>
<li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active':'' }}">
  <i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>

<div class="nav-section">Rekap Absensi</div>
<li><a href="{{ route('admin.data-jurusan') }}" class="nav-link {{ request()->routeIs('admin.data-jurusan') ? 'active':'' }}">
  <i class="bi bi-diagram-3 me-2"></i>Data Jurusan & Siswa</a></li>

<div class="nav-section">Data Master</div>
<li><a href="{{ route('admin.kelas') }}" class="nav-link {{ request()->routeIs('admin.kelas*') ? 'active':'' }}">
  <i class="bi bi-building me-2"></i>Kelola Kelas</a></li>
<li><a href="{{ route('admin.guru') }}" class="nav-link {{ request()->routeIs('admin.guru*') ? 'active':'' }}">
  <i class="bi bi-person-badge me-2"></i>Data Guru</a></li>
<li><a href="{{ route('admin.siswa') }}" class="nav-link {{ request()->routeIs('admin.siswa*') ? 'active':'' }}">
  <i class="bi bi-people me-2"></i>Data Siswa</a></li>

<div class="nav-section">Import / Export</div>
<li><a href="{{ route('admin.excel.template') }}" class="nav-link">
  <i class="bi bi-file-earmark-arrow-down me-2"></i>Download Template CSV</a></li>
