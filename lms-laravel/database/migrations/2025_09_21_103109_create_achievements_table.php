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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'quiz_perfect', 'course_completed', 'streak_7', etc.
            $table->string('title');
            $table->text('description');
            $table->integer('points_earned')->default(0);
            $table->json('metadata')->nullable(); // Additional data about the achievement
            $table->timestamp('achieved_at');
            $table->timestamps();
            
            $table->index(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
