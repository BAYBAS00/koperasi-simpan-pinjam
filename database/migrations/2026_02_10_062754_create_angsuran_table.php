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
        Schema::create('angsuran', function (Blueprint $table) {

            $table->id();

            $table->string('kode_angsuran')
                ->unique();

            $table->unsignedBigInteger('id_pinjaman')
                ->index();

            $table->date('tanggal_bayar')
                ->index();

            $table->decimal('jumlah_bayar', 15, 2);

            $table->enum('status_bayar', ['belum', 'lunas'])
                ->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};
