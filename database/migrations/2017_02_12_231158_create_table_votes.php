<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('votes')) {
            Schema::create('votes', function(Blueprint $table) {
                // Columns
                $table->increments('id');
                $table->integer('answers_id');
                $table->ipAddress('ip');
                $table->string('cookie');

                // Constraints
                $table->foreign('answers_id')
                    ->references('id')
                    ->on('answers')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                // Index
                $table->index('ip', 'ip_idx');
                $table->index('cookie', 'cookie_idx');
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
        Schema::dropIfExists('votes');
    }
}
