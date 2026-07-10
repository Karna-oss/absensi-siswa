<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','Sistem Absensi')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root { --sw: 250px; }
body  { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; font-size: .92rem; }

/* ── Sidebar ── */
.sidebar {
    width: var(--sw); min-height: 100vh;
    background: linear-gradient(180deg, #1e3a5f 0%, #1d4ed8 100%);
    position: fixed; top: 0; left: 0; z-index: 200;
    display: flex; flex-direction: column;
}
.sidebar .brand {
    color: #fff; font-weight: 700; font-size: 1rem;
    padding: 1.2rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,.12);
}
.sidebar .nav-section {
    color: rgba(255,255,255,.4); font-size: .68rem; font-weight: 700;
    letter-spacing: .08em; padding: .9rem 1.5rem .25rem; text-transform: uppercase;
}
.sidebar .nav-link {
    color: rgba(255,255,255,.82); padding: .5rem 1.4rem;
    border-radius: 8px; margin: 2px 8px; font-size: .86rem; transition: all .18s;
}
.sidebar .nav-link:hover,
.sidebar .nav-link.active { background: rgba(255,255,255,.18); color: #fff; }
.sidebar-foot {
    padding: 1rem; border-top: 1px solid rgba(255,255,255,.1); margin-top: auto;
}

/* ── Main ── */
.main { margin-left: var(--sw); padding: 1.8rem; min-height: 100vh; }
.card { border: none; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.card-header { border-radius: 12px 12px 0 0 !important; }
.stat-card   { border-radius: 12px; padding: 1.3rem; color: #fff; }

/* ── Badge status ── */
.badge-hadir { background: #dcfce7; color: #166534; }
.badge-izin  { background: #dbeafe; color: #1e40af; }
.badge-sakit { background: #fef9c3; color: #854d0e; }
.badge-alpha { background: #fee2e2; color: #991b1b; }
.badge-belum { background: #f3f4f6; color: #6b7280; }

/* ── Jurusan Tree ── */
.jurusan-card {
    border: 2px solid #e2e8f0; border-radius: 12px; margin-bottom: 1.2rem;
    overflow: hidden;
}
.jurusan-header {
    background: linear-gradient(135deg, #1d4ed8, #3b82f6);
    color: #fff; padding: .9rem 1.2rem;
    display: flex; align-items: center; justify-content: space-between;
    cursor: pointer; user-select: none;
}
.kelas-block {
    border-top: 1px solid #e2e8f0; padding: 0;
}
.kelas-header {
    background: #f8fafc; padding: .6rem 1.2rem;
    font-weight: 600; color: #374151;
    border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; gap: .5rem;
}
.table th { font-size: .78rem; font-weight: 700; color: #6b7280;
    text-transform: uppercase; letter-spacing: .04em; }
</style>
@stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<nav class="sidebar">
  <div class="brand"><i class="bi bi-mortarboard-fill me-2"></i>Absensi Siswa</div>
  <ul class="nav flex-column mt-1 flex-grow-1 overflow-auto">
    @yield('sidebar-menu')
  </ul>
  <div class="sidebar-foot">
    <div class="text-white-50 small mb-2">
      <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->username ?? '' }}
      <span class="badge bg-white text-primary ms-1" style="font-size:.65rem">{{ auth()->user()->role ?? '' }}</span>
    </div>
    <form action="{{ route('logout') }}" method="POST">@csrf
      <button class="btn btn-sm btn-outline-light w-100">
        <i class="bi bi-box-arrow-right me-1"></i>Logout
      </button>
    </form>
  </div>
</nav>

{{-- MAIN --}}
<main class="main">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 py-2">
      <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 py-2">
      <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
