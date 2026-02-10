<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimpananMaster extends Model
{
    use HasFactory;

    protected $table = 'simpanan_master';

    protected $fillable = [
        'id_anggota',
        'kode_simpanan_master',
        'no_rekening',
        'saldo',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

    public function details()
    {
        return $this->hasMany(SimpananDetail::class, 'id_master');
    }
}
