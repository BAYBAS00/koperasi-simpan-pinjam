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
        Schema::create('simpanan_master', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('id_anggota')
                ->unique()
                ->index();

            $table->string('kode_simpanan_master')
                ->unique();

            $table->string('no_rekening')
                ->unique()
                ->nullable();

            $table->decimal('saldo', 15, 2)
                ->default(0)
                ->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanan_master');
    }
};
