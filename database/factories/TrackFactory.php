<?php

namespace Database\Factories;

use App\Enums\AgeRating;
use App\Models\Holder;
use App\Models\Track;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Track>
 */
class TrackFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3, true),
            'lyric' => fake()->paragraphs(4, true),
            'audio_url' => fake()->url(),
            'cover_url' => fake()->imageUrl(),
            'duration' => fake()->numberBetween(30000, 600000),
            'age_rating' => fake()->randomElement(AgeRating::cases())->value,
            'play_count' => fake()->numberBetween(0, 1_000_000),
            'copyright_holder_id' => Holder::factory(),
            'licensed_at' => fake()->dateTimeBetween('-1 year'),
            'license_expires_at' => fake()->optional(0.7)->dateTimeBetween('now', '+2 years'),
            'version' => '1.0.0',
            'released_at' => fake()->dateTimeBetween('-5 years'),
            'is_active' => fake()->boolean(80),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['is_active' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function withoutLyrics(): static
    {
        return $this->state(fn () => ['lyric' => null]);
    }

    public function withoutAudio(): static
    {
        return $this->state(fn () => ['audio_url' => null]);
    }

    public function withoutCover(): static
    {
        return $this->state(fn () => ['cover_url' => null]);
    }
}
