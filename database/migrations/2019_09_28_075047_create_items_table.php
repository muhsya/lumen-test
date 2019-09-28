<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('checklist_id')->unsigned()->nullable();
            $table->integer('assignee_id')->unsigned()->nullable();
            $table->string('type')->nullable();
            $table->text('description');
            $table->boolean('is_completed');
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('due')->nullable();
            $table->integer('urgency');
            $table->string('updated_by');
            $table->string('created_by');
            $table->integer('task_id');
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('checklist_id')->references('id')->on('checklists')->onDelete('set null');
            $table->foreign('assignee_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
