<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {$table->engine = 'InnoDB';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('name');
            $table->boolean('sex');
            $table->date('birthday');
            $table->string('hometown')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('admins');
    }
}
