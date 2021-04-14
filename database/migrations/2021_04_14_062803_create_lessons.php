<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('syllabus_id');
            $table->unsignedTinyInteger('number');
            $table->text('content');
            $table->unsignedTinyInteger('satellite');
            $table->unsignedTinyInteger('type');
            $table->timestamps();

            $table->unique(['syllabus_id', 'number']);

            $table->foreign('syllabus_id')
                ->references('id')
                ->on('syllabi')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
