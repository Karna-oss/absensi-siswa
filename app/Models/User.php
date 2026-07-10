<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id_user';
    protected $fillable   = ['username', 'password', 'role'];
    protected $hidden     = ['password', 'remember_token'];

    // Wajib: beritahu Laravel pakai kolom 'username' bukan 'email'
    public function getAuthIdentifierName(): string { return 'username'; }

    public function siswa() { return $this->hasOne(Siswa::class, 'id_user', 'id_user'); }
    public function guru()  { return $this->hasOne(Guru::class,  'id_user', 'id_user'); }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isGuru(): bool  { return $this->role === 'guru'; }
    public function isSiswa(): bool { return $this->role === 'siswa'; }
}
