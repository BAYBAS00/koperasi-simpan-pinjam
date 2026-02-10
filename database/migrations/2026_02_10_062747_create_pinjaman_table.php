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
        Schema::create('pinjaman', function (Blueprint $table) {

            $table->id();

            $table->string('kode_pinjaman')
                ->unique();

            $table->unsignedBigInteger('id_anggota')
                ->index();

            $table->unsignedBigInteger('id_pengurus')
                ->nullable()
                ->index();

            $table->date('tanggal_pengajuan');

            $table->decimal('jumlah', 15, 2);
            $table->integer('tenor');

            $table->decimal('bunga', 5, 2);
            $table->decimal('cicilan', 15, 2);

            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak',
                'lunas',
            ])->index();

            $table->dateTime('tanggal_persetujuan')->nullable();

            $table->timestamps();

            // ⭐ SUPER INDEX
            $table->index(['id_anggota', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
