<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bts', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bts');
            $table->string('nama_user');
            $table->string('telepon');
            $table->year('tahun_awal');
            $table->date('jatuh_tempo');
            $table->decimal('nominal_pertahun', 15, 2);
            $table->text('keterangan')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif']);
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
        Schema::dropIfExists('bts');
    }
}
