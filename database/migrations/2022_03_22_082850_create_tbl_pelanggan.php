<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPelanggan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pelanggan', function (Blueprint $table) {
            $table->string('kodepelanggan', 50)->primary();
            $table->string('namapelanggan', 50);
            $table->string('noteleponpelanggan', 50);
            $table->string('alamatpelanggan', 255);
            $table->tinyInteger('statuspelanggan');
            $table->dateTime('dateaddpelanggan');
            $table->dateTime('dateupdpelanggan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pelanggan');
    }
}
