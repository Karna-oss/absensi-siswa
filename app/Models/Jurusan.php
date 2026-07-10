<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table      = 'jurusan';
    protected $primaryKey = 'id_jurusan';
    protected $fillable   = ['kode', 'nama'];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_jurusan');
    }

    public function siswa()
    {
        return $this->hasManyThrough(Siswa::class, Kelas::class, 'id_jurusan', 'id_kelas', 'id_jurusan', 'id_kelas');
    }
}
