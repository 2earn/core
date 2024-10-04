<?php

use App\Models\SurveyQuestion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const TABLE_NAME = 'survey_response_items';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surveyResponse_id')->foreign('surveyResponse_id')->references('id')->on('surveyResponse')->onDelete('cascade');
            $table->unsignedBigInteger('surveyQuestion_id')->foreign('surveyQuestion_id')->references('id')->on('SurveyQuestion')->onDelete('cascade');
            $table->unsignedBigInteger('surveyQuestionChoice_id')->foreign('surveyQuestionChoice_id')->references('id')->on('surveyQuestionChoice')->onDelete('cascade');
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
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
