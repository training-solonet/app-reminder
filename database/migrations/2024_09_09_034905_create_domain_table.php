<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainTable extends Migration
{
    public function up()
    {
        Schema::create('domain', function (Blueprint $table) {
            $table->id();
            $table->string('nama_domain')->unique();
            $table->string('nama_perusahaan');
            $table->date('tgl_expired');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('domain');
    }
}