<?php
namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $siswa    = auth()->user()->siswa->load('kelas.jurusan');
        $bulanIni = Absensi::where('id_siswa', $siswa->id_siswa)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('siswa.dashboard', compact('siswa', 'bulanIni'));
    }

    public function riwayat(Request $request)
    {
        $siswa = auth()->user()->siswa;
        $query = Absensi::where('id_siswa', $siswa->id_siswa)
            ->with('guru')
            ->orderBy('tanggal', 'desc');

        if ($request->bulan) {
            [$y, $m] = explode('-', $request->bulan);
            $query->whereYear('tanggal', $y)->whereMonth('tanggal', $m);
        }
        if ($request->dari)   $query->whereDate('tanggal', '>=', $request->dari);
        if ($request->sampai) $query->whereDate('tanggal', '<=', $request->sampai);

        $absensi = $query->paginate(20)->withQueryString();
        $stats = [
            'hadir' => (clone $query)->where('status','hadir')->count(),
            'izin'  => (clone $query)->where('status','izin')->count(),
            'sakit' => (clone $query)->where('status','sakit')->count(),
            'alpha' => (clone $query)->where('status','alpha')->count(),
        ];

        return view('siswa.riwayat', compact('absensi','stats','siswa'));
    }

    public function profil()
    {
        $siswa = auth()->user()->siswa->load('kelas.jurusan');
        return view('siswa.profil', compact('siswa'));
    }
}
