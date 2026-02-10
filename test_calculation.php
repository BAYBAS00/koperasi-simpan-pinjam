<?php

require __DIR__.'/bootstrap/app.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "TESTING PINJAMAN SERVICE CALCULATION:\n";
$result = App\Services\PinjamanService::hitung(1000000, 12, 1);
echo "Pinjaman: Rp 1.000.000\n";
echo "Tenor: 12 bulan\n";
echo "Bunga: 1% per bulan (Flat)\n";
echo "---\n";
echo 'Total Bunga: Rp '.number_format($result['total_bunga'], 0)."\n";
echo 'Total Pembayaran: Rp '.number_format($result['total_bayar'], 0)."\n";
echo 'Cicilan/bulan: Rp '.number_format($result['cicilan'], 0)."\n";
echo "\nRUMUS: Total Bunga = Pokok × (Bunga% / 100) × Tenor\n";
echo 'Contoh: 1.000.000 × (1 / 100) × 12 = '.number_format(1000000 * 0.01 * 12, 0)."\n";
