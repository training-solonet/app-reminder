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
        Schema::create('tb_pembayaran_bulanan', function (Blueprint $table) {
            $table->id();
            $table->string('pengguna');
            $table->string('no_telp');
            $table->text('keterangan');
            $table->foreignId('id_jenis_pembayaran')->constrained('tb_jenis_pembayaran');
            $table->enum('status_bayar', ['lunas', 'belum-lunas']);
            $table->string('bukti')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pembayaran_bulanan');
    }
};