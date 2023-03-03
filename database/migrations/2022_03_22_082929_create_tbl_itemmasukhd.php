<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblItemmasukhd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_itemmasukhd', function (Blueprint $table) {
            $table->string('kodeitemmasuk', 50)->primary();
            $table->string('kodesupplier', 50);
            $table->string('kodeuser', 50);
            $table->date('tanggalitemmasuk');
            $table->text('keteranganitemmasuk');
            $table->dateTime('dateadditemmasuk');
            $table->dateTime('dateupditemmasuk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_itemmasukhd');
    }
}
