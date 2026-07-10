<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('guru', function (Blueprint $table) {
            $table->id('id_guru');
            $table->string('nama');
            $table->string('nip', 30)->nullable()->unique(); // NIP guru
            $table->foreignId('id_user')
                  ->constrained('users','id_user')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('guru'); }
};
