<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guides', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('unit');
            $table->date('term');
            $table->string('subject');
            $table->integer('fileentry_id');
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
        Schema::drop('guides');
    }
}
