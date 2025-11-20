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
        Schema::create('platform_change_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('platform_id');
            $table->json('changes'); // Store all the fields that were changed
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable(); // User who requested the change
            $table->unsignedBigInteger('reviewed_by')->nullable(); // User who approved/rejected
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->foreign('platform_id')->references('id')->on('platforms')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['platform_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_change_requests');
    }
};

