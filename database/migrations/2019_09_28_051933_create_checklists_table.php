<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->string('object_domain');
            $table->string('object_id');
            $table->text('description');
            $table->boolean('is_completed');
            $table->dateTime('due')->nullable();
            $table->integer('urgency');
            $table->dateTime('completed_at')->nullable();
            $table->integer('task_id');
            $table->string('updated_by');
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
        Schema::dropIfExists('checklists');
    }
}
