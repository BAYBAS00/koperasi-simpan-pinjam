<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';

    protected $fillable = [
        'id_user',
        'kode_anggota',
        'tanggal_lahir',
        'nama',
        'alamat',
        'telepon',
        'tanggal_daftar',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_daftar' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class, 'id_anggota');
    }

    public function simpananMaster()
    {
        return $this->hasMany(SimpananMaster::class, 'id_anggota');
    }
}
