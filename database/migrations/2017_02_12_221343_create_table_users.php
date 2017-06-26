<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function(Blueprint $table) {
                $table->increments('id');
                $table->text('email')->unique();
                $table->text('password');
                $table->jsonb('profile')->nullable();
                $table->timestamp('created')->default(DB::raw('now()'));
                $table->timestamp('updated')->nullable();
                $table->timestamp('last_login')->nullable();
            });
        }
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
