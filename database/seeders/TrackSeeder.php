<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Track;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrackSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $artistIds = Artist::pluck('id');
        $albumIds = Album::pluck('id');
        $genreIds = Genre::pluck('id');

        Track::factory(50)->create()->each(function (Track $track) use ($artistIds, $albumIds, $genreIds) {
            $track->artists()->attach($artistIds->random(rand(1, 3)));
            $track->albums()->attach($albumIds->random(rand(1, 2)));
            $track->genres()->attach($genreIds->random(rand(1, 3)));
        });
    }
}
