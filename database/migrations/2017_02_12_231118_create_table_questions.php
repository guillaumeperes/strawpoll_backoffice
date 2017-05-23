<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('questions')) {
            Schema::create('questions', function(Blueprint $table) {
                // Columns
                $table->increments('id');
                $table->integer('polls_id');
                $table->text('question');
                $table->integer('position')->default('0');

                // Constraints
                $table->foreign('polls_id')
                    ->references('id')
                    ->on('polls')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('questions');
    }
}
