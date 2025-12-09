<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('commission_formulas', function (Blueprint $table) {
            $table->integer('step')->nullable()->after('id');
            $table->float('rate', 8, 2)->nullable()->after('step');
            $table->unsignedTinyInteger('stars')->nullable()->comment('Rating from 1 to 5 stars')->after('rate');
        });

        Schema::rename('commission_formulas', 'plan_labels');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('plan_labels', 'commission_formulas');

        Schema::table('commission_formulas', function (Blueprint $table) {
            $table->dropColumn(['step', 'rate', 'stars']);
        });
    }
};

