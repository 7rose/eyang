<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('org_id');
            $table->integer('type_id');
            $table->integer('quota');
            $table->string('name')->unique();
            $table->string('url');
            $table->string('img')->nullable();
            $table->integer('zm')->nullable();
            $table->boolean('fs')->default(false);
            $table->boolean('show')->default(true);
            $table->text('content')->nullable();
            $table->jsonb('info')->nullable();
            $table->jsonb('config')->nullable();
            $table->integer('created_by');
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
        Schema::dropIfExists('products');
    }
}

