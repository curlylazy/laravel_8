<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item', function (Blueprint $table) {
            $table->string('kodeitem', 50)->primary();
            $table->string('kodekategori', 50);
            $table->string('satuan', 50);
            $table->string('namaitem', 50);
            $table->text('keterangan')->nullable();
            $table->integer('stok');
            $table->integer('stokminimum');
            $table->string('gambar', 255)->nullable();
            $table->tinyInteger('statusitem');
            $table->dateTime('dateadditem');
            $table->dateTime('dateupditem');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_item');
    }
}
