<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimpananDetail extends Model
{
    use HasFactory;

    protected $table = 'simpanan_detail';

    protected $fillable = [
        'id_master',
        'jumlah',
        'tanggal',
        'jenis_transaksi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function master()
    {
        return $this->belongsTo(SimpananMaster::class, 'id_master');
    }
}
