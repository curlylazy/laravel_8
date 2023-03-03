<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user', function (Blueprint $table) {
            $table->string('kodeuser', 50)->primary();
            $table->string('username', 50);
            $table->string('password', 255);
            $table->string('nama', 100);
            $table->string('email', 100);
            $table->string('nohp', 50);
            $table->string('alamat', 255);
            $table->string('akses', 15);
            $table->char('jk', 1);
            $table->tinyInteger('statususer');
            $table->dateTime('dateadduser');
            $table->dateTime('dateupduser');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_user');
    }
}
