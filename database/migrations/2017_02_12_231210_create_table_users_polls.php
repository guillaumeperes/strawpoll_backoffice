<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsersPolls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users_polls')) {
            Schema::create('users_polls', function(Blueprint $table) {
                // Columns
                $table->integer('polls_id');
                $table->integer('users_id');

                // Constraints
                $table->foreign('polls_id')
                    ->references('id')
                    ->on('polls')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->foreign('users_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
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
        Schema::dropIfExists('users_polls');
    }
}
