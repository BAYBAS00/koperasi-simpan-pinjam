<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('simpanan_detail', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('id_master')
                ->index();

            $table->decimal('jumlah', 15, 2);

            $table->date('tanggal')
                ->index();

            $table->enum('jenis_transaksi', [
                'menyimpan',
                'penarikan',
            ])->index();

            $table->timestamps();

            // ⭐ HISTORICAL QUERY BOOST
            $table->index(['id_master', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanan_detail');
    }
};
