<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->foreignId('id_jurusan')
                  ->constrained('jurusan','id_jurusan')
                  ->onDelete('cascade');
            $table->string('nama_kelas');   // X RPL 1, X RPL 2
            $table->string('tingkat',5);    // X, XI, XII
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kelas'); }
};
