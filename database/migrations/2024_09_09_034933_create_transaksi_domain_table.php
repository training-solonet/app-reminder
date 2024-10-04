<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiDomainTable extends Migration
{
    public function up()
    {
        Schema::create('transaksi_domain', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_transaksi');
            $table->unsignedBigInteger('domain_id');
            $table->decimal('nominal', 10, 2);
            $table->enum('status', ['lunas', 'belum-lunas']);
            $table->string('bukti')->nullable();
            $table->integer('masa_perpanjang')->default(1);
            $table->timestamps();

            $table->foreign('domain_id')->references('id')->on('domain');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_domain');
    }
}
