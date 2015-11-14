<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEffectOfGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('effect_of_guides', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('guide_id');
            $table->datetime('date');
            $table->string('note');
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
        Schema::drop('effect_of_guides');
    }
}
