<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeToModelIdInSyllabusModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('syllabus_model', function (Blueprint $table): void {
            $table->unsignedBigInteger('model_id')->after('type');
            $table->unique(['syllabus_id', 'model_id']);

            $table->dropUnique('models_syllabus_id_type_unique');
            $table->dropColumn('type');

            $table->foreign('model_id')->references('id')->on('models')
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
        Schema::table('syllabus_model', function (Blueprint $table) {
            $table->string('type')->after('model_id');
            $table->unique(['syllabus_id', 'type'], 'models_syllabus_id_type_unique');

            $table->dropForeign(['model_id']);
            $table->dropUnique(['syllabus_id', 'model_id']);
            $table->dropColumn('model_id');
        });
    }
}
