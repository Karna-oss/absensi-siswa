<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table      = 'siswa';
    protected $primaryKey = 'id_siswa';
    protected $fillable   = ['nama', 'nis', 'id_kelas', 'id_user'];

    public function kelas()   { return $this->belongsTo(Kelas::class, 'id_kelas'); }
    public function user()    { return $this->belongsTo(User::class,  'id_user'); }
    public function absensi() { return $this->hasMany(Absensi::class, 'id_siswa'); }

    // Helper: ambil absensi di tanggal tertentu
    public function absensiTanggal($tanggal)
    {
        return $this->absensi()->whereDate('tanggal', $tanggal)->first();
    }
}
