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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(false);

            // BAGIAN 1: IDENTITAS CHANNEL
            $table->string('channel_name_final')->nullable();
            $table->string('business_email')->nullable();
            $table->string('youtube_channel_link')->nullable();
            $table->string('social_handle_twitter')->nullable();
            $table->string('social_handle_threads')->nullable();
            $table->string('social_handle_linkedin')->nullable();

            // BAGIAN 2: FONDASI & VISI
            $table->text('channel_description')->nullable();
            $table->text('long_term_vision')->nullable();
            $table->text('channel_mission')->nullable();
            $table->json('core_values')->nullable(); // Menyimpan sebagai JSON array

            // BAGIAN 3: TARGET AUDIENS
            $table->text('primary_audience_persona')->nullable();
            $table->text('secondary_audience_persona')->nullable();

            // BAGIAN 4: ANALISIS NICHE & USP
            $table->text('main_niche')->nullable();
            $table->text('sub_niche')->nullable();
            $table->json('competitor_analysis')->nullable(); // Menyimpan sebagai JSON
            $table->text('unique_selling_proposition')->nullable();

            // BAGIAN 5: STRATEGI KONTEN AWAL
            $table->json('main_content_formats')->nullable(); // Menyimpan sebagai JSON
            $table->text('content_pillars')->nullable();
            $table->string('upload_frequency')->nullable();
            $table->text('initial_video_ideas')->nullable();

            // BAGIAN 6: BRANDING VISUAL & AUDIO
            $table->text('logo_concept')->nullable();
            $table->text('banner_concept')->nullable();
            $table->string('intro_outro_music')->nullable();
            $table->json('color_palette')->nullable(); // Menyimpan sebagai JSON
            $table->string('main_font')->nullable();

            // BAGIAN 7: MONETISASI & TUJUAN AWAL
            $table->json('monetization_strategy')->nullable(); // Menyimpan sebagai JSON
            $table->text('kpi_targets_3_months')->nullable();

            // BAGIAN 8: ALUR KERJA PRODUKSI AI
            $table->text('ai_production_workflow')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
