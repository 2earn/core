<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('translatetab', function (Blueprint $table) {
            $table->text('name')->change();
            $table->text('value')->change();
            $table->text('valueFr')->change();
            $table->text('valueEn')->change();
            $table->text('valueEs')->change();
            $table->text('valueTr')->change();
            $table->text('valueRu')->change();
            $table->text('valueDe')->change();

        });
    }


    public function down(): void
    {
        Schema::table('translatetab', function (Blueprint $table) {
            $table->longText('name')->change();
            $table->longText('value')->change();
            $table->longText('valueFr')->change();
            $table->longText('valueEn')->change();
            $table->longText('valueEs')->change();
            $table->longText('valueTr')->change();
            $table->longText('valueRu')->change();
            $table->longText('valueDe')->change();
        });
    }
};
