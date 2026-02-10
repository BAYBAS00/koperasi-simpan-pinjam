<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    use HasFactory;

    protected $table = 'pengurus';

    protected $fillable = [
        'kode_pengurus',
        'nama',
        'alamat',
        'telepon',
        'tanggal_daftar',
        'id_user',
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class, 'id_pengurus');
    }
}
