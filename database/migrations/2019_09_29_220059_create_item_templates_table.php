<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->unsigned()->nullable();
            $table->string('description');
            $table->integer('urgency');
            $table->integer('due_interval');
            $table->string('due_unit');
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('templates')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_templates');
    }
}
