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
        Schema::create('deal_product_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained('deals')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->enum('action', ['added', 'removed'])->default('added');
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['deal_id', 'item_id']);
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deal_product_changes');
    }
};

