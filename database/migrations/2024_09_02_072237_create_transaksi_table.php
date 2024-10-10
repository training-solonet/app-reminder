<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_transaksi', ['Pinjam', 'Bayar Pajak', 'Servis']);
            $table->string('nama_motor');
            $table->string('nota_pajak')->nullable();
            $table->unsignedBigInteger('id_karyawan');
            $table->unsignedBigInteger('plat_nomor');
            $table->decimal('nominal', 15, 2);
            $table->timestamps();

            $table->foreign('id_karyawan')->references('id')->on('karyawan');
            $table->foreign('plat_nomor')->references('id')->on('motors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}
