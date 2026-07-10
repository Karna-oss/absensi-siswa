<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table      = 'guru';
    protected $primaryKey = 'id_guru';
    protected $fillable   = ['nama', 'nip', 'id_user'];

    public function user()    { return $this->belongsTo(User::class, 'id_user'); }
    public function absensi() { return $this->hasMany(Absensi::class, 'id_guru'); }
}
