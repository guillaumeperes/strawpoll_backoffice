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
                $table->text('username')->unique();
                $table->text('password');
                $table->text('salt');
                $table->jsonb('profile')->nullable();
                $table->timestamp('created')->default(DB::raw('now()'));
                $table->timestamp('updated')->nullable();
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
