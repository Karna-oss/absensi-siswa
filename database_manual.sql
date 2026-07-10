-- ================================================================
-- DATABASE: absensi_siswa
-- Sistem Absensi Siswa Laravel v3
-- Import via phpMyAdmin atau jalankan di MySQL CLI
-- ================================================================

CREATE DATABASE IF NOT EXISTS `absensi_siswa`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `absensi_siswa`;

-- ── 1. USERS ─────────────────────────────────────────────────
CREATE TABLE `users` (
  `id_user`        BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username`       VARCHAR(255)    NOT NULL UNIQUE,
  `password`       VARCHAR(255)    NOT NULL,
  `role`           ENUM('admin','guru','siswa') NOT NULL,
  `remember_token` VARCHAR(100)    DEFAULT NULL,
  `created_at`     TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── 2. JURUSAN (6 jurusan tetap) ──────────────────────────────
CREATE TABLE `jurusan` (
  `id_jurusan` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `kode`       VARCHAR(10)     NOT NULL UNIQUE,
  `nama`       VARCHAR(255)    NOT NULL,
  `created_at` TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── 3. KELAS (2 per jurusan default, bisa ditambah) ───────────
CREATE TABLE `kelas` (
  `id_kelas`   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_jurusan` BIGINT UNSIGNED NOT NULL,
  `nama_kelas` VARCHAR(255)    NOT NULL,
  `tingkat`    VARCHAR(5)      NOT NULL,
  `created_at` TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan`(`id_jurusan`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── 4. GURU (dengan NIP) ─────────────────────────────────────
CREATE TABLE `guru` (
  `id_guru`    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nama`       VARCHAR(100)    NOT NULL,
  `nip`        VARCHAR(30)     DEFAULT NULL UNIQUE,
  `id_user`    BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_user`) REFERENCES `users`(`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── 5. SISWA ─────────────────────────────────────────────────
CREATE TABLE `siswa` (
  `id_siswa`   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nama`       VARCHAR(100)    NOT NULL,
  `nis`        VARCHAR(20)     NOT NULL UNIQUE,
  `id_kelas`   BIGINT UNSIGNED NOT NULL,
  `id_user`    BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_kelas`) REFERENCES `kelas`(`id_kelas`) ON DELETE CASCADE,
  FOREIGN KEY (`id_user`)  REFERENCES `users`(`id_user`)  ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── 6. ABSENSI ───────────────────────────────────────────────
CREATE TABLE `absensi` (
  `id_absensi` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_siswa`   BIGINT UNSIGNED NOT NULL,
  `id_kelas`   BIGINT UNSIGNED NOT NULL,
  `id_guru`    BIGINT UNSIGNED NOT NULL,
  `tanggal`    DATE            NOT NULL,
  `status`     ENUM('hadir','izin','sakit','alpha') NOT NULL,
  `keterangan` TEXT            DEFAULT NULL,
  `created_at` TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_siswa_tanggal` (`id_siswa`, `tanggal`),
  FOREIGN KEY (`id_siswa`) REFERENCES `siswa`(`id_siswa`) ON DELETE CASCADE,
  FOREIGN KEY (`id_kelas`) REFERENCES `kelas`(`id_kelas`) ON DELETE CASCADE,
  FOREIGN KEY (`id_guru`)  REFERENCES `guru`(`id_guru`)   ON DELETE CASCADE
) ENGINE=InnoDB;

-- ================================================================
-- DATA AWAL (password semua akun = "password")
-- Hash bcrypt standard Laravel
-- ================================================================

INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin',        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('guru_andi',    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru'),
('guru_budi',    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru'),
('siswa_ahmad',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
('siswa_bela',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
('siswa_candra', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
('siswa_dewi',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
('siswa_eko',    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
('siswa_fitri',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
('siswa_galih',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
('siswa_hana',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
('siswa_ivan',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
('siswa_julia',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa');

INSERT INTO `jurusan` (`kode`, `nama`) VALUES
('RPL', 'Rekayasa Perangkat Lunak'),
('TKJ', 'Teknik Komputer dan Jaringan'),
('MM',  'Multimedia'),
('AK',  'Akuntansi'),
('TKR', 'Teknik Kendaraan Ringan'),
('TSM', 'Teknik Sepeda Motor');

INSERT INTO `kelas` (`id_jurusan`, `nama_kelas`, `tingkat`) VALUES
(1, 'X RPL 1', 'X'), (1, 'X RPL 2', 'X'),
(2, 'X TKJ 1', 'X'), (2, 'X TKJ 2', 'X'),
(3, 'X MM 1',  'X'), (3, 'X MM 2',  'X'),
(4, 'X AK 1',  'X'), (4, 'X AK 2',  'X'),
(5, 'X TKR 1', 'X'), (5, 'X TKR 2', 'X'),
(6, 'X TSM 1', 'X'), (6, 'X TSM 2', 'X');

INSERT INTO `guru` (`nama`, `nip`, `id_user`) VALUES
('Andi Susanto, S.Pd',  '198501012010011001', 2),
('Budi Santoso, S.Kom', '198703022012011002', 3);

INSERT INTO `siswa` (`nama`, `nis`, `id_kelas`, `id_user`) VALUES
('Ahmad Fauzi',    '2024001', 1,  4),
('Bela Safitri',   '2024002', 1,  5),
('Candra Wijaya',  '2024003', 1,  6),
('Dewi Rahayu',    '2024004', 2,  7),
('Eko Prasetyo',   '2024005', 2,  8),
('Fitri Amalia',   '2024006', 3,  9),
('Galih Pratama',  '2024007', 3,  10),
('Hana Pertiwi',   '2024008', 4,  11),
('Ivan Kurniawan', '2024009', 5,  12),
('Julia Sari',     '2024010', 5,  13);
