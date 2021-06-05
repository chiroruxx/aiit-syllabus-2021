<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'scores',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('syllabus_id')->unique();
                $table->unsignedTinyInteger('participants');
                $table->unsignedTinyInteger('score_5');
                $table->unsignedTinyInteger('score_4');
                $table->unsignedTinyInteger('score_3');
                $table->unsignedTinyInteger('score_2');
                $table->unsignedTinyInteger('score_1');
                $table->unsignedTinyInteger('score_0');
                $table->timestamps();

                $table->foreign('syllabus_id')
                    ->references('id')
                    ->on('syllabi')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
