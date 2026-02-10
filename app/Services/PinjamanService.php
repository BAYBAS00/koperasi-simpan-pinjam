<?php

namespace App\Services;

class PinjamanService
{
    public static function hitung($jumlah, $tenor, $bunga = 1)
    {
        // Jika bunga 0 atau null, gunakan default 1%
        $bunga = $bunga ?: 1;

        $totalBunga = $jumlah * ($bunga / 100) * $tenor;
        $totalBayar = $jumlah + $totalBunga;
        $cicilan = $totalBayar / $tenor;

        return [
            'total_bunga' => round($totalBunga, 2),
            'total_bayar' => round($totalBayar, 2),
            'cicilan' => round($cicilan, 2),
        ];
    }
}
