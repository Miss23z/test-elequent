<?php

namespace App\Models;

use App\Enums\AgeRating;
use Database\Factories\TrackFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'title',
    'lyric',
    'audio_url',
    'cover_url',
    'duration',
    'age_rating',
    'play_count',
    'copyright_holder_id',
    'licensed_at',
    'license_expires_at',
    'version',
    'released_at',
    'is_active',
])]
class Track extends Model
{
    /** @use HasFactory<TrackFactory> */
    use HasFactory;
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'lyric' => 'array',
            'licensed_at' => 'datetime',
            'license_expires_at' => 'datetime',
            'released_at' => 'datetime',
            'is_active' => 'boolean',
            'age_rating' => AgeRating::class,
            'duration' => 'integer',
            'play_count' => 'integer',
        ];
    }

    public function artists(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class)->withTimestamps();
    }

    public function albums(): BelongsToMany
    {
        return $this->belongsToMany(Album::class)->withTimestamps();
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class)->withTimestamps();
    }

    public function copyrightHolder(): BelongsTo
    {
        return $this->belongsTo(Holder::class);
    }
}
