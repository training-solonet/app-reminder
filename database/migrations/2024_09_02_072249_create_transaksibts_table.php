<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiBtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_bts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bts_id')->constrained('bts')->onDelete('cascade');
            $table->date('tgl_transaksi');
            $table->decimal('nominal', 15, 2);
            $table->string('bukti')->nullable();
            $table->enum('status', ['lunas', 'belum_lunas']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_bts');
    }
}
