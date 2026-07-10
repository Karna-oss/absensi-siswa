<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Jurusan, Kelas, Guru, Siswa};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── ADMIN ─────────────────────────────────────────────────
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // ── 6 JURUSAN ─────────────────────────────────────────────
        $jurusanData = [
            ['kode' => 'RPL', 'nama' => 'Rekayasa Perangkat Lunak'],
            ['kode' => 'TKJ', 'nama' => 'Teknik Komputer dan Jaringan'],
            ['kode' => 'MM',  'nama' => 'Multimedia'],
            ['kode' => 'AK',  'nama' => 'Akuntansi'],
            ['kode' => 'TKR', 'nama' => 'Teknik Kendaraan Ringan'],
            ['kode' => 'TSM', 'nama' => 'Teknik Sepeda Motor'],
        ];

        $jurusanMap = [];
        foreach ($jurusanData as $j) {
            $jurusanMap[$j['kode']] = Jurusan::create($j);
        }

        // ── 2 KELAS PER JURUSAN (12 kelas total) ──────────────────
        $kelasMap = [];
        foreach ($jurusanMap as $kode => $jurusan) {
            for ($i = 1; $i <= 2; $i++) {
                $kelasMap["{$kode}_{$i}"] = Kelas::create([
                    'id_jurusan' => $jurusan->id_jurusan,
                    'nama_kelas' => "X {$kode} {$i}",
                    'tingkat'    => 'X',
                ]);
            }
        }

        // ── 2 GURU (dengan NIP) ───────────────────────────────────
        $uGuru1 = User::create(['username' => 'guru_andi', 'password' => Hash::make('password'), 'role' => 'guru']);
        Guru::create(['nama' => 'Andi Susanto, S.Pd', 'nip' => '198501012010011001', 'id_user' => $uGuru1->id_user]);

        $uGuru2 = User::create(['username' => 'guru_budi', 'password' => Hash::make('password'), 'role' => 'guru']);
        Guru::create(['nama' => 'Budi Santoso, S.Kom', 'nip' => '198703022012011002', 'id_user' => $uGuru2->id_user]);

        // ── SISWA CONTOH (tiap kelas RPL & TKJ) ──────────────────
        $siswaData = [
            ['nama' => 'Ahmad Fauzi',    'nis' => '2024001', 'kelas' => 'RPL_1', 'username' => 'siswa_ahmad'],
            ['nama' => 'Bela Safitri',   'nis' => '2024002', 'kelas' => 'RPL_1', 'username' => 'siswa_bela'],
            ['nama' => 'Candra Wijaya',  'nis' => '2024003', 'kelas' => 'RPL_1', 'username' => 'siswa_candra'],
            ['nama' => 'Dewi Rahayu',    'nis' => '2024004', 'kelas' => 'RPL_2', 'username' => 'siswa_dewi'],
            ['nama' => 'Eko Prasetyo',   'nis' => '2024005', 'kelas' => 'RPL_2', 'username' => 'siswa_eko'],
            ['nama' => 'Fitri Amalia',   'nis' => '2024006', 'kelas' => 'TKJ_1', 'username' => 'siswa_fitri'],
            ['nama' => 'Galih Pratama',  'nis' => '2024007', 'kelas' => 'TKJ_1', 'username' => 'siswa_galih'],
            ['nama' => 'Hana Pertiwi',   'nis' => '2024008', 'kelas' => 'TKJ_2', 'username' => 'siswa_hana'],
            ['nama' => 'Ivan Kurniawan', 'nis' => '2024009', 'kelas' => 'MM_1',  'username' => 'siswa_ivan'],
            ['nama' => 'Julia Sari',     'nis' => '2024010', 'kelas' => 'MM_1',  'username' => 'siswa_julia'],
        ];

        foreach ($siswaData as $s) {
            $user = User::create([
                'username' => $s['username'],
                'password' => Hash::make('password'),
                'role'     => 'siswa',
            ]);
            Siswa::create([
                'nama'     => $s['nama'],
                'nis'      => $s['nis'],
                'id_kelas' => $kelasMap[$s['kelas']]->id_kelas,
                'id_user'  => $user->id_user,
            ]);
        }

        $this->command->info('✓ Seeder selesai!');
        $this->command->info('  6 Jurusan | 12 Kelas | 2 Guru | 10 Siswa');
        $this->command->info('  Login: admin/password | guru_andi/password | siswa_ahmad/password');
    }
}
