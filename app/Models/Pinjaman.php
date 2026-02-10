<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = [
        'id_anggota',
        'id_pengurus',
        'kode_pinjaman',
        'tanggal_pengajuan',
        'jumlah',
        'tenor',
        'bunga',
        'cicilan',
        'status',
        'tanggal_persetujuan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_persetujuan' => 'datetime',
        'jumlah' => 'integer',
        'cicilan' => 'integer',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

    public function pengurus()
    {
        return $this->belongsTo(Pengurus::class, 'id_pengurus');
    }

    public function angsuran()
    {
        return $this->hasMany(Angsuran::class, 'id_pinjaman');
    }
}
