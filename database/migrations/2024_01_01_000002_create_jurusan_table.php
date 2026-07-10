<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id('id_jurusan');
            $table->string('kode', 10)->unique();   // RPL, TKJ, MM, AK, TKR, TSM
            $table->string('nama');                  // Rekayasa Perangkat Lunak, dst
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('jurusan'); }
};
