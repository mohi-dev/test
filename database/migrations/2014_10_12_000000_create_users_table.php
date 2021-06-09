<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('user_name')->unique();
            $table->string('password');
            $table->unsignedInteger('time_created')->nullable();
            $table->unsignedInteger('time_updated')->nullable();
            $table->unsignedInteger('deleted_at')->nullable();
            $table->engine = 'InnoDB';
        });

        $models = array(
            ['name' => 'User', 'user_name' => 'Admin', 'password' => 'admin123' ,'time_created' => time()],
        );

        DB::table('users')->insert($models);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
