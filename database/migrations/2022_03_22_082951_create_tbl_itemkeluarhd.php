<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblItemkeluarhd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_itemkeluarhd', function (Blueprint $table) {
            $table->string('kodeitemkeluar', 50)->primary();
            $table->string('kodepelanggan', 50);
            $table->string('kodeuser', 50);
            $table->date('tanggalitemkeluar');
            $table->text('keteranganitemkeluar');
            $table->dateTime('dateadditemkeluar');
            $table->dateTime('dateupditemkeluar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_itemkeluarhd');
    }
}
