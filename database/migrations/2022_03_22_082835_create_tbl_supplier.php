<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSupplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_supplier', function (Blueprint $table) {
            $table->string('kodesupplier', 50)->primary();
            $table->string('namasupplier', 50);
            $table->string('alamatsupplier', 100);
            $table->string('noteleponsupplier', 50);
            $table->tinyInteger('statussupplier');
            $table->dateTime('dateaddsupplier');
            $table->dateTime('dateupdsupplier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_supplier');
    }
}
