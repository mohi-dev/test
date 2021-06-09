<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('cat_id')->constrained('cats');
            $table->string('title');
            $table->string('title_en');
            $table->integer('price');
            $table->text('description');
            $table->string('pic');
            $table->integer('sort');
            $table->unsignedInteger('time_created')->nullable();
            $table->unsignedInteger('time_updated')->nullable();
            $table->unsignedInteger('deleted_at')->nullable();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
