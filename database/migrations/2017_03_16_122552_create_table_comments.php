<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function($table) {
                $table->increments('id');
                $table->integer('users_id');
                $table->integer('polls_id');
                $table->text('comment');
                $table->timestamp('published')->default(DB::raw('now()'));

                $table->foreign('users_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('SET NULL')
                    ->onUpdate('CASCADE');

                $table->foreign('polls_id')
                    ->references('id')
                    ->on('polls')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
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
        Schema::dropIfExists('comments');
    }
}
