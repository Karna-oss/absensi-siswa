<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    /**
     * POST /api/login
     * Body: { "username": "...", "password": "..." }
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Username atau password salah',
            ], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id_user'  => $user->id_user,
                'username' => $user->username,
                'role'     => $user->role,
            ],
        ]);
    }

    /**
     * GET /api/me
     * Header: Authorization: Bearer {token}
     */
    public function me(Request $request)
    {
        $user = $request->user();

        $profile = [
            'id_user'  => $user->id_user,
            'username' => $user->username,
            'role'     => $user->role,
        ];

        if ($user->isGuru() && $user->guru) {
            $profile['nama'] = $user->guru->nama;
            $profile['nip']  = $user->guru->nip;
        }

        if ($user->isSiswa() && $user->siswa) {
            $profile['nama']     = $user->siswa->nama;
            $profile['nis']      = $user->siswa->nis;
            $profile['id_kelas'] = $user->siswa->id_kelas;
        }

        return response()->json($profile);
    }

    /**
     * GET /api/absensi
     * Header: Authorization: Bearer {token}
     *
     * - Kalau login sebagai siswa -> tampilkan riwayat absensi siswa itu sendiri
     * - Kalau login sebagai guru  -> tampilkan absensi yang diinput guru itu
     * - Kalau login sebagai admin -> tampilkan semua absensi
     */
    public function listAbsensi(Request $request)
    {
        $user = $request->user();

        if ($user->isSiswa()) {
            if (!$user->siswa) {
                return response()->json(['message' => 'Data siswa tidak ditemukan'], 404);
            }
            $absensi = $user->siswa->absensi()->with(['kelas', 'guru'])->latest('tanggal')->get();
        } elseif ($user->isGuru()) {
            if (!$user->guru) {
                return response()->json(['message' => 'Data guru tidak ditemukan'], 404);
            }
            $absensi = $user->guru->absensi()->with(['siswa', 'kelas'])->latest('tanggal')->get();
        } elseif ($user->isAdmin()) {
            $absensi = \App\Models\Absensi::with(['siswa', 'kelas', 'guru'])->latest('tanggal')->get();
        } else {
            return response()->json(['message' => 'Role tidak dikenali'], 403);
        }

        return response()->json([
            'total' => $absensi->count(),
            'data'  => $absensi,
        ]);
    }

    /**
     * POST /api/absensi
     * Header: Authorization: Bearer {token}
     * Body: { "id_siswa": 1, "id_kelas": 1, "status": "hadir", "keterangan": "...", "tanggal": "2026-07-17" (opsional) }
     *
     * Hanya guru yang boleh input absensi.
     * Field "tanggal" bersifat OPSIONAL — kalau tidak dikirim, otomatis
     * memakai tanggal server hari ini (jadi selalu "ter-update" mengikuti hari berjalan).
     */
    public function storeAbsensi(Request $request)
    {
        $user = $request->user();

        if (!$user->isGuru()) {
            return response()->json(['message' => 'Hanya guru yang boleh input absensi'], 403);
        }

        $request->validate([
            'id_siswa'    => 'required|exists:siswa,id_siswa',
            'id_kelas'    => 'required|exists:kelas,id_kelas',
            'tanggal'     => 'nullable|date',
            'status'      => 'required|in:hadir,izin,sakit,alpha',
            'keterangan'  => 'nullable|string',
        ]);

        // Kalau tanggal tidak dikirim di request, otomatis pakai tanggal server hari ini.
        // Inilah yang membuat absensi "otomatis mengikuti tanggal berjalan" setiap harinya.
        $tanggal = $request->tanggal ?? now()->toDateString();

        $absensi = \App\Models\Absensi::updateOrCreate(
            [
                'id_siswa' => $request->id_siswa,
                'tanggal'  => $tanggal,   // <-- pakai variable $tanggal, BUKAN $request->tanggal
            ],
            [
                'id_kelas'   => $request->id_kelas,
                'id_guru'    => $user->guru->id_guru,
                'status'     => $request->status,
                'keterangan' => $request->keterangan,
            ]
        );

        return response()->json([
            'message' => 'Absensi berhasil disimpan',
            'tanggal' => $tanggal,
            'data'    => $absensi,
        ], 201);
    }

    /**
     * POST /api/logout
     * Header: Authorization: Bearer {token}
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }
}