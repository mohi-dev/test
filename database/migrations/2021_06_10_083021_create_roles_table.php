<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200);
            $table->unsignedInteger('time_created')->nullable();
            $table->unsignedInteger('time_updated')->nullable();
            $table->unsignedInteger('deleted_at')->nullable();
            $table->engine = 'InnoDB';
        });

        $models = array(
            ['name' => 'access', 'time_created' => time()],
            ['name' => 'not_access', 'time_created' => time()],
        );

        DB::table('roles')->insert($models);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('roles');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
