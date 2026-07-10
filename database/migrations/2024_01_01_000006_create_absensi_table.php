<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->foreignId('id_siswa')->constrained('siswa','id_siswa')->onDelete('cascade');
            $table->foreignId('id_kelas')->constrained('kelas','id_kelas')->onDelete('cascade');
            $table->foreignId('id_guru')->constrained('guru','id_guru')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status',['hadir','izin','sakit','alpha']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['id_siswa','tanggal']); // 1 siswa = 1 status per hari
        });
    }
    public function down(): void { Schema::dropIfExists('absensi'); }
};
