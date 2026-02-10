<?php

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Anggota;
use App\Models\Pengurus;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create test user for pengurus
$user1 = User::firstOrCreate(
    ['username' => 'pengurus'],
    ['password' => Hash::make('123456'), 'role' => 'pengurus']
);

// Create pengurus
$pengurus = Pengurus::firstOrCreate(
    ['id_user' => $user1->id],
    [
        'kode_pengurus' => 'PG001',
        'nama' => 'Pengurus Test',
        'ttl' => '1990-01-01',
        'alamat' => 'Jalan Raya, Jakarta',
        'telepon' => '081234567890',
    ]
);

// Create test user for anggota
$user2 = User::firstOrCreate(
    ['username' => 'anggota'],
    ['password' => Hash::make('123456'), 'role' => 'anggota']
);

// Create anggota
$anggota = Anggota::firstOrCreate(
    ['id_user' => $user2->id],
    [
        'kode_anggota' => 'AG001',
        'nama' => 'Anggota Test',
        'tanggal_lahir' => '1995-05-15',
        'alamat' => 'Jalan Merpati, Jakarta',
        'telepon' => '082345678901',
    ]
);

echo "Seeder berhasil dijalankan!\n";
echo "Username Pengurus: pengurus / Password: 123456\n";
echo "Username Anggota: anggota / Password: 123456\n";
