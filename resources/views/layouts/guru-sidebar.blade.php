<div class="nav-section">Utama</div>
<li><a href="{{ route('guru.dashboard') }}" class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active':'' }}">
  <i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>

<div class="nav-section">Rekap Absensi</div>
<li><a href="{{ route('guru.data-jurusan') }}" class="nav-link {{ request()->routeIs('guru.data-jurusan') ? 'active':'' }}">
  <i class="bi bi-diagram-3 me-2"></i>Data Jurusan & Siswa</a></li>

<div class="nav-section">Input Absensi</div>
<li><a href="{{ route('guru.input') }}" class="nav-link {{ request()->routeIs('guru.input') ? 'active':'' }}">
  <i class="bi bi-calendar-check me-2"></i>Input Absensi</a></li>
