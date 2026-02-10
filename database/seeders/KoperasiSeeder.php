<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KoperasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {

            /*
            ====================================
            SUPER ADMIN
            ====================================
            */
            $adminId = DB::table('users')->insertGetId([
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'pengurus',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('pengurus')->insert([
                'id_user' => $adminId,
                'kode_pengurus' => 'PGR-'.now()->format('Ymd').'-00001',
                'nama' => 'Super Admin',
                'alamat' => 'Kantor Pusat',
                'telepon' => '0800000000',
                'tanggal_daftar' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            /*
            ====================================
            TAMBAH 2 PENGURUS LAGI
            ====================================
            */
            for ($i = 1; $i <= 2; $i++) {

                $userId = DB::table('users')->insertGetId([
                    'username' => 'pengurus'.$i,
                    'password' => Hash::make('password'),
                    'role' => 'pengurus',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('pengurus')->insert([
                    'id_user' => $userId,
                    'kode_pengurus' => 'PGR-'.now()->format('Ymd').'-'.rand(10000, 99999),
                    'nama' => 'Pengurus '.$i,
                    'alamat' => 'Alamat Pengurus',
                    'telepon' => '08123'.rand(100000, 999999),
                    'tanggal_daftar' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            /*
            ====================================
            BUAT 15 ANGGOTA
            ====================================
            */

            for ($i = 1; $i <= 15; $i++) {

                $userId = DB::table('users')->insertGetId([
                    'username' => 'anggota'.$i,
                    'password' => Hash::make('password'),
                    'role' => 'anggota',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $anggotaId = DB::table('anggotas')->insertGetId([
                    'id_user' => $userId,
                    'kode_anggota' => 'ANG-'.now()->format('Ymd').'-'.rand(10000, 99999),
                    'nama' => 'Anggota '.$i,
                    'tanggal_lahir' => now()->subYears(rand(20, 40)),
                    'alamat' => 'Alamat Anggota '.$i,
                    'telepon' => '08222'.rand(100000, 999999),
                    'tanggal_daftar' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                /*
                ====================================
                SIMPANAN MASTER
                ====================================
                */

                $saldoAwal = rand(500000, 3000000);

                $masterId = DB::table('simpanan_master')->insertGetId([
                    'id_anggota' => $anggotaId,
                    'kode_simpanan_master' => 'SIMP-'.rand(10000, 99999),
                    'no_rekening' => 'REK'.rand(10000000, 99999999),
                    'saldo' => $saldoAwal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // ledger simpanan awal
                DB::table('simpanan_detail')->insert([
                    'id_master' => $masterId,
                    'jumlah' => $saldoAwal,
                    'tanggal' => now(),
                    'jenis_transaksi' => 'menyimpan',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                /*
                ====================================
                BUAT PINJAMAN UNTUK SEBAGIAN ANGGOTA
                ====================================
                */

                if (rand(0, 1)) {

                    $jumlah = rand(1000000, 5000000);
                    $tenor = rand(6, 12);
                    $bunga = 1;

                    $totalBunga = $jumlah * ($bunga / 100) * $tenor;
                    $cicilan = ($jumlah + $totalBunga) / $tenor;

                    $pinjamanId = DB::table('pinjaman')->insertGetId([
                        'kode_pinjaman' => 'PIN-'.rand(10000, 99999),
                        'id_anggota' => $anggotaId,
                        'id_pengurus' => 1,
                        'tanggal_pengajuan' => now(),
                        'jumlah' => $jumlah,
                        'tenor' => $tenor,
                        'bunga' => $bunga,
                        'cicilan' => $cicilan,
                        'status' => 'disetujui',
                        'tanggal_persetujuan' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    /*
                    ====================================
                    ANGSURAN SAMPLE
                    ====================================
                    */

                    $dibayar = rand(1, $tenor);

                    for ($a = 1; $a <= $dibayar; $a++) {

                        DB::table('angsuran')->insert([
                            'kode_angsuran' => 'ANGS-'.rand(10000, 99999),
                            'id_pinjaman' => $pinjamanId,
                            'tanggal_bayar' => now()->subMonths($tenor - $a),
                            'jumlah_bayar' => $cicilan,
                            'status_bayar' => 'lunas',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    if ($dibayar >= $tenor) {
                        DB::table('pinjaman')
                            ->where('id', $pinjamanId)
                            ->update(['status' => 'lunas']);
                    }
                }
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
            throw $e;
        }
    }
}
