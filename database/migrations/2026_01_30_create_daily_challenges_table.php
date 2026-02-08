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
        Schema::create('daily_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('question_id')->nullable()->constrained()->onDelete('set null');
            $table->text('submitted_answer')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('score')->default(0);
            $table->integer('duration')->nullable(); // in seconds
            $table->integer('attempt_number')->default(1);
            $table->timestamp('completed_at')->nullable();
            $table->json('metadata')->nullable(); // Store additional data like time spent, hints used, etc.
            $table->timestamps();

            // Indexes for faster queries
            $table->index('user_id');
            $table->index('game_id');
            $table->index('created_at');
            $table->index('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_challenges');
    }
};
