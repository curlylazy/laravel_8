<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblItemkeluardt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_itemkeluardt', function (Blueprint $table) {
            $table->string('kodeitemkeluardt', 50)->primary();
            $table->string('kodeitemkeluar', 50);
            $table->string('kodeitem', 50);
            $table->integer('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_itemkeluardt');
    }
}
