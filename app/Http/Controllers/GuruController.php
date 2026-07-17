<?php
namespace App\Http\Controllers;

use App\Models\{Jurusan, Kelas, Siswa, Absensi, Guru};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function dashboard()
    {
        $guru = auth()->user()->guru;
        return view('guru.dashboard', [
            'guru'           => $guru,
            'absensiHariIni' => Absensi::where('id_guru', $guru->id_guru)->whereDate('tanggal', today())->count(),
            'totalInput'     => Absensi::where('id_guru', $guru->id_guru)->count(),
        ]);
    }

    public function dataJurusan(Request $request)
    {
        $tanggal   = $request->tanggal ?? date('Y-m-d');
        $idJurusan = $request->id_jurusan;

        $jurusanQuery = Jurusan::with([
            'kelas' => function ($q) use ($tanggal) {
                $q->with([
                    'siswa' => function ($sq) use ($tanggal) {
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
        $jurusanList  = Jurusan::all();

        $stats = [
            'hadir' => Absensi::whereDate('tanggal', $tanggal)->where('status','hadir')->count(),
            'izin'  => Absensi::whereDate('tanggal', $tanggal)->where('status','izin')->count(),
            'sakit' => Absensi::whereDate('tanggal', $tanggal)->where('status','sakit')->count(),
            'alpha' => Absensi::whereDate('tanggal', $tanggal)->where('status','alpha')->count(),
            'belum' => Siswa::count() - Absensi::whereDate('tanggal', $tanggal)->count(),
        ];

        return view('guru.data-jurusan', compact('semuaJurusan','jurusanList','tanggal','stats'));
    }

    // FIX: load SEMUA kelas sekaligus, filter pakai JavaScript di view
    public function inputAbsensi(Request $request)
    {
        $jurusan         = Jurusan::all();
        $kelas           = Kelas::with('jurusan')->get(); // <- semua kelas, filter di JS
        $siswaList       = collect();
        $absensiExisting = collect();

        if ($request->id_kelas && $request->tanggal) {
            $siswaList = Siswa::where('id_kelas', $request->id_kelas)->get();
            $absensiExisting = Absensi::where('id_kelas', $request->id_kelas)
                ->whereDate('tanggal', $request->tanggal)
                ->pluck('status', 'id_siswa');
        }

        return view('guru.input-absensi', compact('jurusan','kelas','siswaList','absensiExisting'));
    }

    public function simpanAbsensi(Request $request)
    {
        $request->validate([
            'id_kelas'  => 'required|exists:kelas,id_kelas',
            'tanggal'   => 'required|date',
            'absensi'   => 'required|array',
            'absensi.*' => 'in:hadir,izin,sakit,alpha',
        ]);

        $guru = auth()->user()->guru;

        DB::transaction(function () use ($request, $guru) {
            foreach ($request->absensi as $id_siswa => $status) {
                Absensi::updateOrCreate(
                    ['id_siswa' => $id_siswa, 'tanggal' => $request->tanggal],
                    [
                        'id_kelas'   => $request->id_kelas,
                        'id_guru'    => $guru->id_guru,
                        'status'     => $status,
                        'keterangan' => $request->keterangan[$id_siswa] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('guru.data-jurusan')
            ->with('success', 'Absensi berhasil disimpan untuk ' . count($request->absensi) . ' siswa!');
    }

    public function uploadBukti(Request $request, $id_absensi)
    {
        $request->validate([
            'bukti_foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $absensi = Absensi::findOrFail($id_absensi);

        if ($absensi->bukti_foto) {
            \Storage::disk('public')->delete($absensi->bukti_foto);
        }

        $path = $request->file('bukti_foto')->store('bukti-absensi', 'public');

        $absensi->update(['bukti_foto' => $path]);

        return response()->json([
            'message' => 'Foto bukti berhasil diupload',
            'path'    => $path,
            'url'     => \Storage::url($path),
        ]);
    }
    }
