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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->json('setting')->nullable();
            $table->timestamps();
        });

        Schema::create('game_plays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('best_score')->default(0);
            $table->string('reward_type')->nullable();
            $table->timestamps();

            $table->unique(['game_id', 'user_id']);
        });

        Schema::create('game_play_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_play_id');
            $table->integer('score')->nullable();
            $table->timestamp('played_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('reward_issued_at')->nullable();
            $table->string('scene');
            $table->timestamps();

            $table->foreign('game_play_id')->references('id')->on('game_plays')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_play_histories');
        Schema::dropIfExists('game_plays');
        Schema::dropIfExists('games');
    }
};
