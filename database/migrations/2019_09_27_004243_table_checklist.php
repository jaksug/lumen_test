<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableChecklist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type',200);
            $table->bigInteger('task_id');
            $table->string('object_domain',200);
            $table->string('object_id',200);
            $table->text('description');
            $table->boolean('is_completed');
            $table->dateTime('completed_at')->nullable();
            $table->string('due',200)->nullable();
            $table->string('updated_by',200)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at');
            $table->bigInteger('urgency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklist');
    }
}
