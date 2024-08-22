<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    const TABLE_NAME = 'surveys';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('enabled')->default(false);
            $table->boolean('published')->default(false);
            $table->string('status')->default(\Core\Enum\StatusSurvey::NEW->value);
            $table->boolean('updatable')->default(false);

            $table->dateTime('startDate')->nullable();
            $table->dateTime('endDate')->nullable();
            $table->dateTime('enableDate')->nullable();
            $table->dateTime('disableDate')->nullable();
            $table->dateTime('publishDate')->nullable();
            $table->dateTime('unpublishDate')->nullable();
            $table->dateTime('openDate')->nullable();
            $table->dateTime('closeDate')->nullable();
            $table->dateTime('archivedDate')->nullable();

            $table->boolean('show')->default(1);
            $table->boolean('showAfterArchiving')->default(1);
            $table->integer('showResult')->default(1);
            $table->integer('commentable')->default(1);
            $table->integer('likable')->default(1);
            $table->integer('showAttchivementPourcentage')->default(1);
            $table->integer('showAttchivementChrono')->default(1);

            $table->integer('goals')->nullable();

            $table->text('description')->nullable();
            $table->text('disabledBtnDescription')->nullable();
            $table->text('disabledResult')->nullable();
            $table->text('disabledComment')->nullable();
            $table->text('disabledLike')->nullable();
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
