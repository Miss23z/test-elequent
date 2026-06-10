<?php

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Holder;
use App\Models\Track;
use Illuminate\Console\Command;

class SeedTracks extends Command
{
    protected $signature = 'tracks:seed {count=10 : Number of tracks}';

    protected $description = 'Seed any number of tracks with random relationships';

    public function handle(): void
    {
        $count = (int) $this->argument('count');

        if (Holder::count() === 0) {
            Holder::factory(5)->create();
            $this->info('Created 5 copyright holders.');
        }

        if (Artist::count() === 0) {
            Artist::factory(20)->create();
            $this->info('Created 20 artists.');
        }

        if (Album::count() === 0) {
            Album::factory(30)->create();
            $this->info('Created 30 albums.');
        }

        if (Genre::count() === 0) {
            Genre::factory(15)->create();
            $this->info('Created 15 genres.');
        }

        $artistIds = Artist::pluck('id');
        $albumIds = Album::pluck('id');
        $genreIds = Genre::pluck('id');

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        Track::factory($count)->create()->each(function (Track $track) use ($artistIds, $albumIds, $genreIds, $bar) {
            $track->artists()->attach($artistIds->random(rand(1, 3)));
            $track->albums()->attach($albumIds->random(rand(1, 2)));
            $track->genres()->attach($genreIds->random(rand(1, 3)));
            $bar->advance();
        });

        $bar->finish();
        $this->newLine();
        $this->info("Done: {$count} tracks seeded.");
    }
}
