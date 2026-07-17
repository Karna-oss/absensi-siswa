<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table      = 'absensi';
    protected $primaryKey = 'id_absensi';
    protected $fillable   = ['id_siswa','id_kelas','id_guru','tanggal','status','keterangan','bukti_foto'];
    protected $casts      = ['tanggal' => 'date'];

    public function siswa() { return $this->belongsTo(Siswa::class, 'id_siswa'); }
    public function kelas() { return $this->belongsTo(Kelas::class, 'id_kelas'); }
    public function guru()  { return $this->belongsTo(Guru::class,  'id_guru'); }
}
