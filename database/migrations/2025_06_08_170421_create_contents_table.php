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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // --- Core Info ---
            $table->string('title')->nullable();
            $table->string('target_keywords')->nullable();
            $table->string('content_pillar')->nullable();
            $table->string('video_format')->nullable(); // 'long', 'short'
            $table->string('specific_audience')->nullable();
            $table->text('main_goal')->nullable();

            // --- Content Details (as TEXT for V1) ---
            $table->text('script_outline')->nullable();
            $table->text('visual_assets_needed')->nullable();
            $table->text('audio_assets_needed')->nullable();
            $table->text('youtube_description')->nullable();
            $table->text('youtube_tags')->nullable();
            $table->text('production_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
