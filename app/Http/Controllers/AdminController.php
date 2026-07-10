<?php
namespace App\Http\Controllers;

use App\Models\{User, Jurusan, Kelas, Guru, Siswa, Absensi};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, DB};

class AdminController extends Controller
{
    // ══════════════════════════════════════════════════════
    // DASHBOARD
    // ══════════════════════════════════════════════════════
    public function dashboard()
    {
        return view('admin.dashboard', [
            'totalJurusan'   => Jurusan::count(),
            'totalKelas'     => Kelas::count(),
            'totalGuru'      => Guru::count(),
            'totalSiswa'     => Siswa::count(),
            'absensiHariIni' => Absensi::whereDate('tanggal', today())->count(),
            'jurusanList'    => Jurusan::withCount(['kelas','siswa'])->get(),
        ]);
    }

    // ══════════════════════════════════════════════════════
    // DATA REKAP: JURUSAN > KELAS > SISWA (sudah & belum absen)
    // Ini halaman utama yang menampilkan SEMUA data terstruktur
    // ══════════════════════════════════════════════════════
    public function dataJurusan(Request $request)
    {
        $tanggal   = $request->tanggal ?? date('Y-m-d');
        $idJurusan = $request->id_jurusan;

        // Ambil semua jurusan beserta kelas dan siswa
        $jurusanQuery = Jurusan::with([
            'kelas' => function ($q) use ($tanggal, $idJurusan) {
                $q->with([
                    'siswa' => function ($sq) use ($tanggal) {
                        // Load absensi di tanggal ini untuk tiap siswa
                        $sq->with(['absensi' => function ($aq) use ($tanggal) {
                            $aq->whereDate('tanggal', $tanggal);
                        }]);
                    }
                ])->withCount('siswa');
            }
        ]);

        if ($idJurusan) {
            $jurusanQuery->where('id_jurusan', $idJurusan);
        }

        $semuaJurusan = $jurusanQuery->get();
        $jurusanList  = Jurusan::all(); // untuk filter dropdown

        // Statistik total hari ini
        $stats = [
            'hadir' => Absensi::whereDate('tanggal', $tanggal)->where('status','hadir')->count(),
            'izin'  => Absensi::whereDate('tanggal', $tanggal)->where('status','izin')->count(),
            'sakit' => Absensi::whereDate('tanggal', $tanggal)->where('status','sakit')->count(),
            'alpha' => Absensi::whereDate('tanggal', $tanggal)->where('status','alpha')->count(),
            'belum' => Siswa::count() - Absensi::whereDate('tanggal', $tanggal)->count(),
        ];

        return view('admin.data-jurusan', compact('semuaJurusan','jurusanList','tanggal','stats'));
    }

    // ══════════════════════════════════════════════════════
    // KELOLA KELAS (tambah kelas baru di jurusan yang ada)
    // ══════════════════════════════════════════════════════
    public function kelasIndex()
    {
        $kelas   = Kelas::with('jurusan')->withCount('siswa')->orderBy('id_jurusan')->get();
        $jurusan = Jurusan::all();
        return view('admin.kelas.index', compact('kelas', 'jurusan'));
    }

    public function kelasStore(Request $request)
    {
        $request->validate([
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'nama_kelas' => 'required|string|max:50',
            'tingkat'    => 'required|in:X,XI,XII',
        ]);
        Kelas::create($request->only('id_jurusan','nama_kelas','tingkat'));
        return redirect()->route('admin.kelas')->with('success', 'Kelas baru berhasil ditambahkan!');
    }

    public function kelasDestroy($id)
    {
        Kelas::findOrFail($id)->delete();
        return redirect()->route('admin.kelas')->with('success', 'Kelas dihapus!');
    }

    // ══════════════════════════════════════════════════════
    // GURU
    // ══════════════════════════════════════════════════════
    public function guruIndex()
    {
        $guru = Guru::with('user')->paginate(15);
        return view('admin.guru.index', compact('guru'));
    }

    public function guruCreate() { return view('admin.guru.create'); }

    public function guruStore(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'nip'      => 'nullable|string|max:30|unique:guru,nip',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|min:6',
        ]);
        DB::transaction(function () use ($request) {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => 'guru',
            ]);
            Guru::create([
                'nama'    => $request->nama,
                'nip'     => $request->nip,
                'id_user' => $user->id_user,
            ]);
        });
        return redirect()->route('admin.guru')->with('success', 'Guru berhasil ditambahkan!');
    }

    public function guruEdit($id)
    {
        $guru = Guru::with('user')->findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    public function guruUpdate(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        $request->validate([
            'nama' => 'required|string|max:100',
            'nip'  => 'nullable|string|max:30|unique:guru,nip,'.$guru->id_guru.',id_guru',
        ]);
        $guru->update($request->only('nama','nip'));
        return redirect()->route('admin.guru')->with('success', 'Data guru diperbarui!');
    }

    public function guruDestroy($id)
    {
        Guru::findOrFail($id)->user->delete();
        return redirect()->route('admin.guru')->with('success', 'Guru dihapus!');
    }

    // ══════════════════════════════════════════════════════
    // SISWA
    // ══════════════════════════════════════════════════════
    public function siswaIndex(Request $request)
    {
        $siswa = Siswa::with(['kelas.jurusan','user'])
            ->when($request->id_jurusan, fn($q) =>
                $q->whereHas('kelas', fn($q2) => $q2->where('id_jurusan', $request->id_jurusan)))
            ->when($request->id_kelas, fn($q) => $q->where('id_kelas', $request->id_kelas))
            ->paginate(20)->withQueryString();

        $jurusan = Jurusan::all();
        $kelas   = Kelas::with('jurusan')->get();
        return view('admin.siswa.index', compact('siswa','jurusan','kelas'));
    }

    public function siswaCreate()
    {
        $jurusan = Jurusan::with('kelas')->get();
        $kelas   = Kelas::with('jurusan')->get();
        return view('admin.siswa.create', compact('jurusan','kelas'));
    }

    public function siswaStore(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'nis'      => 'required|string|unique:siswa,nis',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|min:6',
        ]);
        DB::transaction(function () use ($request) {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => 'siswa',
            ]);
            Siswa::create([
                'nama'     => $request->nama,
                'nis'      => $request->nis,
                'id_kelas' => $request->id_kelas,
                'id_user'  => $user->id_user,
            ]);
        });
        return redirect()->route('admin.siswa')->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function siswaEdit($id)
    {
        $siswa = Siswa::with(['kelas','user'])->findOrFail($id);
        $kelas = Kelas::with('jurusan')->get();
        return view('admin.siswa.edit', compact('siswa','kelas'));
    }

    public function siswaUpdate(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $request->validate([
            'nama'     => 'required|string|max:100',
            'nis'      => 'required|string|unique:siswa,nis,'.$siswa->id_siswa.',id_siswa',
            'id_kelas' => 'required|exists:kelas,id_kelas',
        ]);
        $siswa->update($request->only('nama','nis','id_kelas'));
        return redirect()->route('admin.siswa')->with('success', 'Data siswa diperbarui!');
    }

    public function siswaDestroy($id)
    {
        Siswa::findOrFail($id)->user->delete();
        return redirect()->route('admin.siswa')->with('success', 'Siswa dihapus!');
    }

    // ══════════════════════════════════════════════════════
    // EDIT / HAPUS ABSENSI
    // ══════════════════════════════════════════════════════
    public function absensiEdit($id)
    {
        $absensi = Absensi::with(['siswa','kelas.jurusan'])->findOrFail($id);
        return view('admin.absensi.edit', compact('absensi'));
    }

    public function absensiUpdate(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:hadir,izin,sakit,alpha', 'keterangan' => 'nullable|string']);
        Absensi::findOrFail($id)->update($request->only('status','keterangan'));
        return redirect()->back()->with('success', 'Absensi diperbarui!');
    }

    public function absensiDestroy($id)
    {
        Absensi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data absensi dihapus!');
    }

    // ══════════════════════════════════════════════════════
    // DOWNLOAD TEMPLATE EXCEL
    // ══════════════════════════════════════════════════════
    public function downloadTemplate()
    {
        // Buat CSV sebagai template (tidak butuh ext GD)
        $filename = 'template_absensi_' . date('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // BOM untuk Excel bisa baca UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom
            fputcsv($file, ['NIS', 'Nama Siswa', 'Kelas', 'Jurusan', 'Tanggal (YYYY-MM-DD)', 'Status', 'Keterangan']);

            // Contoh data dari database
            $siswaList = Siswa::with('kelas.jurusan')->get();
            foreach ($siswaList as $s) {
                fputcsv($file, [
                    $s->nis,
                    $s->nama,
                    $s->kelas->nama_kelas,
                    $s->kelas->jurusan->kode,
                    date('Y-m-d'),
                    'hadir',
                    '',
                ]);
            }

            // Baris kosong + petunjuk
            fputcsv($file, []);
            fputcsv($file, ['=== PETUNJUK ===']);
            fputcsv($file, ['Status yang valid:', 'hadir', 'izin', 'sakit', 'alpha']);
            fputcsv($file, ['Format tanggal:', 'YYYY-MM-DD', 'Contoh: ' . date('Y-m-d')]);
            fputcsv($file, ['Jurusan:', 'RPL', 'TKJ', 'MM', 'AK', 'TKR', 'TSM']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ══════════════════════════════════════════════════════
    // IMPORT EXCEL / CSV
    // ══════════════════════════════════════════════════════
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file     = fopen($request->file('file')->getPathname(), 'r');
        $berhasil = 0;
        $gagal    = 0;
        $errors   = [];
        $isHeader = true;

        // Ambil guru default (admin yang import, pakai guru pertama)
        $guru = Guru::first();
        if (!$guru) {
            return redirect()->back()->with('error', 'Tidak ada data guru. Tambahkan guru terlebih dahulu.');
        }

        while (($row = fgetcsv($file)) !== false) {
            // Skip header & baris kosong & petunjuk
            if ($isHeader) { $isHeader = false; continue; }
            if (empty(trim($row[0] ?? '')) || str_starts_with(trim($row[0] ?? ''), '=')) continue;

            $nis        = trim($row[0] ?? '');
            $tanggal    = trim($row[4] ?? '');
            $status     = strtolower(trim($row[5] ?? ''));
            $keterangan = trim($row[6] ?? '');

            // Cari siswa
            $siswa = Siswa::where('nis', $nis)->first();
            if (!$siswa) {
                $gagal++;
                $errors[] = "NIS $nis: tidak ditemukan di database";
                continue;
            }

            // Validasi tanggal
            try {
                $tgl = \Carbon\Carbon::parse($tanggal)->format('Y-m-d');
            } catch (\Exception $e) {
                $gagal++;
                $errors[] = "NIS $nis: format tanggal '$tanggal' salah";
                continue;
            }

            // Simpan / update
            Absensi::updateOrCreate(
                ['id_siswa' => $siswa->id_siswa, 'tanggal' => $tgl],
                [
                    'id_kelas'   => $siswa->id_kelas,
                    'id_guru'    => $guru->id_guru,
                    'status'     => $status,
                    'keterangan' => $keterangan,
                ]
            );
            $berhasil++;
        }

        fclose($file);

        $msg = "Import selesai: $berhasil data berhasil diimport";
        if ($gagal > 0) {
            $msg .= ", $gagal gagal (" . implode('; ', array_slice($errors, 0, 5)) . ")";
        }

        return redirect()->route('admin.data-jurusan')->with('success', $msg);
    }
}
