<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DuplicationChecks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('duplication_checks')) {
            Schema::create('duplication_checks', function(Blueprint $table) {
                $table->increments('id');
                $table->string('name', 128);
                $table->string('label', 128);
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
        Schema::dropIfExists('duplication_checks');
    }
}
