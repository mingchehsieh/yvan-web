<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rotas', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('date');
            $table->string('title');
            $table->string('d1')->nullable();
            $table->string('d2')->nullable();
            $table->string('d3')->nullable();
            $table->string('d4')->nullable();
            $table->string('d5')->nullable();
            $table->string('d6')->nullable();
            $table->string('d7')->nullable();
            $table->string('d8')->nullable();
            $table->string('d9')->nullable();
            $table->string('d10')->nullable();
            $table->string('d11')->nullable();
            $table->string('d12')->nullable();
            $table->string('d13')->nullable();
            $table->string('d14')->nullable();
            $table->string('d15')->nullable();
            $table->string('d16')->nullable();
            $table->string('d17')->nullable();
            $table->string('d18')->nullable();
            $table->string('d19')->nullable();
            $table->string('d20')->nullable();
            $table->string('d21')->nullable();
            $table->string('d22')->nullable();
            $table->string('d23')->nullable();
            $table->string('d24')->nullable();
            $table->string('d25')->nullable();
            $table->string('d26')->nullable();
            $table->string('d27')->nullable();
            $table->string('d28')->nullable();
            $table->string('d29')->nullable();
            $table->string('d30')->nullable();
            $table->string('d31')->nullable();
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
        Schema::drop('rotas');
    }
}
