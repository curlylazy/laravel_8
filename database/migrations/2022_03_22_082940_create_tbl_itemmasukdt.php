<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblItemmasukdt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_itemmasukdt', function (Blueprint $table) {
            $table->string('kodeitemmasukdt', 50)->primary();
            $table->string('kodeitemmasuk', 50);
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
        Schema::dropIfExists('tbl_itemmasukdt');
    }
}
