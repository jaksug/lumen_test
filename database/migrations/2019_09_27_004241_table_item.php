<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('checklist_id');
            $table->string('type',200)->nullable();
            $table->text('description');
            $table->boolean('is_completed');
            $table->dateTime('completed_at')->nullable();
            $table->string('due',200)->nullable();
            $table->string('urgency',200)->nullable();
            $table->string('updated_by',200)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at');
            $table->string('asignee_id',200)->nullable();
            $table->bigInteger('task_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item');
    }
}
