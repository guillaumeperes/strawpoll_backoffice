<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePolls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('polls')) {
            Schema::create('polls', function(Blueprint $table) {
                // Columns
                $table->increments('id');
                $table->integer('duplication_checks_id');
                $table->boolean('has_captcha');
                $table->boolean('multiple_answers')->default(false);
                $table->integer('users_id')->nullable();
                $table->boolean('is_draft')->default(false);
                $table->timestamp('created')->default(DB::raw('now()'));
                $table->timestamp('updated')->nullable();
                $table->timestamp('published')->nullable();

                // Constraints
                $table->foreign('duplication_checks_id')
                    ->references('id')
                    ->on('duplication_checks')
                    ->onDelete('no action')
                    ->onUpdate('cascade');

                $table->foreign('users_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null')
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
        Schema::dropIfExists('polls');
    }
}
