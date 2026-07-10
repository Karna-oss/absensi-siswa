<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Login - Sistem Absensi Siswa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{background:linear-gradient(135deg,#1e3a5f,#1d4ed8 55%,#3b82f6);min-height:100vh;display:flex;align-items:center;justify-content:center;}
.card{border:none;border-radius:20px;box-shadow:0 25px 60px rgba(0,0,0,.3);width:100%;max-width:420px;}
.login-icon{width:72px;height:72px;background:linear-gradient(135deg,#1d4ed8,#3b82f6);border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:2.2rem;color:#fff;}
.form-control{border-radius:10px;padding:.72rem 1rem;}
.btn-login{background:linear-gradient(135deg,#1d4ed8,#1e40af);border:none;border-radius:10px;padding:.75rem;font-weight:600;}
.hint{background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:.9rem;font-size:.82rem;}
</style>
</head>
<body>
<div class="card p-4 p-md-5">
  <div class="login-icon"><i class="bi bi-mortarboard-fill"></i></div>
  <h5 class="text-center fw-bold mb-1">Sistem Absensi Siswa</h5>
  <p class="text-center text-muted small mb-4">Masuk untuk melanjutkan</p>

  @if($errors->has('login'))
    <div class="alert alert-danger rounded-3 py-2 small">
      <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first('login') }}
    </div>
  @endif

  <form action="{{ route('login.post') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label class="form-label fw-semibold small">Username</label>
      <div class="input-group">
        <span class="input-group-text bg-light"><i class="bi bi-person text-primary"></i></span>
        <input type="text" name="username" value="{{ old('username') }}"
          class="form-control border-start-0" placeholder="Masukkan username" autofocus>
      </div>
    </div>
    <div class="mb-4">
      <label class="form-label fw-semibold small">Password</label>
      <div class="input-group">
        <span class="input-group-text bg-light"><i class="bi bi-lock text-primary"></i></span>
        <input type="password" name="password"
          class="form-control border-start-0" placeholder="Masukkan password">
      </div>
    </div>
    <button type="submit" class="btn btn-primary btn-login w-100 text-white mb-3">
      <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
    </button>
  </form>

  <div class="hint">
    <p class="fw-bold mb-2 text-primary small"><i class="bi bi-info-circle me-1"></i>Akun Demo (password: <code>password</code>)</p>
    <div class="row g-1 small">
      <div class="col-4"><span class="badge bg-danger me-1">Admin</span><br><code>admin</code></div>
      <div class="col-4"><span class="badge bg-success me-1">Guru</span><br><code>guru_andi</code></div>
      <div class="col-4"><span class="badge bg-primary me-1">Siswa</span><br><code>siswa_ahmad</code></div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
