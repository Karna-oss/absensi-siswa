<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Data awal Sistem Absensi Siswa.
     * Password semua akun default: "password"
     * (di-hash ulang lewat Hash::make supaya selalu valid, tidak bergantung
     * pada hash bcrypt lama dari database_manual.sql)
     */
    public function run(): void
    {
        $now = now();
        $defaultPassword = Hash::make('password');

        // ── 1. USERS ─────────────────────────────────────────────
        $users = [
            ['username' => 'admin',        'role' => 'admin'],
            ['username' => 'guru_andi',    'role' => 'guru'],
            ['username' => 'guru_budi',    'role' => 'guru'],
            ['username' => 'siswa_ahmad',  'role' => 'siswa'],
            ['username' => 'siswa_bela',   'role' => 'siswa'],
            ['username' => 'siswa_candra', 'role' => 'siswa'],
            ['username' => 'siswa_dewi',   'role' => 'siswa'],
            ['username' => 'siswa_eko',    'role' => 'siswa'],
            ['username' => 'siswa_fitri',  'role' => 'siswa'],
            ['username' => 'siswa_galih',  'role' => 'siswa'],
            ['username' => 'siswa_hana',   'role' => 'siswa'],
            ['username' => 'siswa_ivan',   'role' => 'siswa'],
            ['username' => 'siswa_julia',  'role' => 'siswa'],
        ];
        foreach ($users as $u) {
            DB::table('users')->insert([
                'username'   => $u['username'],
                'password'   => $defaultPassword,
                'role'       => $u['role'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ── 2. JURUSAN ───────────────────────────────────────────
        $jurusan = [
            ['kode' => 'RPL', 'nama' => 'Rekayasa Perangkat Lunak'],
            ['kode' => 'TKJ', 'nama' => 'Teknik Komputer dan Jaringan'],
            ['kode' => 'MM',  'nama' => 'Multimedia'],
            ['kode' => 'AK',  'nama' => 'Akuntansi'],
            ['kode' => 'TKR', 'nama' => 'Teknik Kendaraan Ringan'],
            ['kode' => 'TSM', 'nama' => 'Teknik Sepeda Motor'],
        ];
        foreach ($jurusan as $j) {
            DB::table('jurusan')->insert([
                'kode'       => $j['kode'],
                'nama'       => $j['nama'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ── 3. KELAS (id_jurusan mengikuti urutan insert di atas: 1..6) ──
        $kelas = [
            ['id_jurusan' => 1, 'nama_kelas' => 'X RPL 1', 'tingkat' => 'X'],
            ['id_jurusan' => 1, 'nama_kelas' => 'X RPL 2', 'tingkat' => 'X'],
            ['id_jurusan' => 2, 'nama_kelas' => 'X TKJ 1', 'tingkat' => 'X'],
            ['id_jurusan' => 2, 'nama_kelas' => 'X TKJ 2', 'tingkat' => 'X'],
            ['id_jurusan' => 3, 'nama_kelas' => 'X MM 1',  'tingkat' => 'X'],
            ['id_jurusan' => 3, 'nama_kelas' => 'X MM 2',  'tingkat' => 'X'],
            ['id_jurusan' => 4, 'nama_kelas' => 'X AK 1',  'tingkat' => 'X'],
            ['id_jurusan' => 4, 'nama_kelas' => 'X AK 2',  'tingkat' => 'X'],
            ['id_jurusan' => 5, 'nama_kelas' => 'X TKR 1', 'tingkat' => 'X'],
            ['id_jurusan' => 5, 'nama_kelas' => 'X TKR 2', 'tingkat' => 'X'],
            ['id_jurusan' => 6, 'nama_kelas' => 'X TSM 1', 'tingkat' => 'X'],
            ['id_jurusan' => 6, 'nama_kelas' => 'X TSM 2', 'tingkat' => 'X'],
        ];
        foreach ($kelas as $k) {
            DB::table('kelas')->insert([
                'id_jurusan' => $k['id_jurusan'],
                'nama_kelas' => $k['nama_kelas'],
                'tingkat'    => $k['tingkat'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ── 4. GURU (id_user mengacu ke urutan users di atas: 2=guru_andi, 3=guru_budi) ──
        $guru = [
            ['nama' => 'Andi Susanto, S.Pd',  'nip' => '198501012010011001', 'id_user' => 2],
            ['nama' => 'Budi Santoso, S.Kom', 'nip' => '198703022012011002', 'id_user' => 3],
        ];
        foreach ($guru as $g) {
            DB::table('guru')->insert([
                'nama'       => $g['nama'],
                'nip'        => $g['nip'],
                'id_user'    => $g['id_user'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ── 5. SISWA (id_kelas & id_user mengacu ke urutan di atas) ──
        $siswa = [
            ['nama' => 'Ahmad Fauzi',    'nis' => '2024001', 'id_kelas' => 1, 'id_user' => 4],
            ['nama' => 'Bela Safitri',   'nis' => '2024002', 'id_kelas' => 1, 'id_user' => 5],
            ['nama' => 'Candra Wijaya',  'nis' => '2024003', 'id_kelas' => 1, 'id_user' => 6],
            ['nama' => 'Dewi Rahayu',    'nis' => '2024004', 'id_kelas' => 2, 'id_user' => 7],
            ['nama' => 'Eko Prasetyo',   'nis' => '2024005', 'id_kelas' => 2, 'id_user' => 8],
            ['nama' => 'Fitri Amalia',   'nis' => '2024006', 'id_kelas' => 3, 'id_user' => 9],
            ['nama' => 'Galih Pratama',  'nis' => '2024007', 'id_kelas' => 3, 'id_user' => 10],
            ['nama' => 'Hana Pertiwi',   'nis' => '2024008', 'id_kelas' => 4, 'id_user' => 11],
            ['nama' => 'Ivan Kurniawan', 'nis' => '2024009', 'id_kelas' => 5, 'id_user' => 12],
            ['nama' => 'Julia Sari',     'nis' => '2024010', 'id_kelas' => 5, 'id_user' => 13],
        ];
        foreach ($siswa as $s) {
            DB::table('siswa')->insert([
                'nama'       => $s['nama'],
                'nis'        => $s['nis'],
                'id_kelas'   => $s['id_kelas'],
                'id_user'    => $s['id_user'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
