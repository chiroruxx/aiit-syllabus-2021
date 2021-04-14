<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syllabi', function (Blueprint $table) {
            $table->id();
            $table->string('name_ja')->index();
            $table->string('name_en');
            $table->unsignedTinyInteger('course')->index();
            $table->unsignedTinyInteger('compulsory');
            $table->unsignedTinyInteger('credit');
            $table->unsignedTinyInteger('quarter');
            $table->string('group');
            $table->string('teacher');
            $table->text('abstract');
            $table->text('purpose');
            $table->text('precondition');
            $table->text('higher_goal');
            $table->text('lower_goal');
            $table->text('outside_learning');
            $table->text('inside_learning');
            $table->text('evaluation');
            $table->text('text');
            $table->text('reference');
            $table->timestamps();
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
        Schema::dropIfExists('syllabi');
    }
}
