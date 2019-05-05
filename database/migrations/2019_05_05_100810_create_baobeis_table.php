<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaobeisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baobeis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id');
            $table->jsonb('info');
            $table->datetime('bb')->nullable();
            $table->datetime('check')->nullable();
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
        Schema::dropIfExists('baobeis');
    }
}
