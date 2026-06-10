<?php

use App\Enums\AgeRating;
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
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->json('lyric')->nullable();
            $table->string('audio_url', 255)->nullable();
            $table->string('cover_url', 255)->nullable();

            $table->unsignedInteger('duration')->comment('Длительность в миллисекундах');

            $table->enum('age_rating', AgeRating::values())->default(AgeRating::Rating0->value);
            $table->unsignedBigInteger('play_count')->default(0);
            $table->foreignId('copyright_holder_id')->constrained('holder');
            $table->timestamp('licensed_at')->nullable();
            $table->timestamp('license_expires_at')->nullable();

            $table->string('version', 10)->default('1.0.0');
            $table->timestamp('released_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('title'); // поиск по названию трека
            $table->index(['is_active', 'released_at']); // выдача активных треков с сортировкой по дате релиза
            $table->index(['is_active', 'play_count']); // выдача активных треков с сортировкой по популярности
            $table->index('license_expires_at'); // мониторинг истекающих лицензий
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
