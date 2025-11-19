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
        Schema::create('commission_formulas', function (Blueprint $table) {
            $table->id();
            $table->decimal('initial_commission', 10, 2)->comment('Initial commission percentage or amount');
            $table->decimal('final_commission', 10, 2)->comment('Final commission percentage or amount');
            $table->string('name')->nullable()->comment('Optional name for the commission formula');
            $table->text('description')->nullable()->comment('Description of the commission formula');
            $table->boolean('is_active')->default(true)->comment('Whether this formula is active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('is_active');
            $table->index(['initial_commission', 'final_commission']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_formulas');
    }
};

