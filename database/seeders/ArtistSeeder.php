<?php

namespace Database\Seeders;

use App\Models\Artist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArtistSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Artist::factory(20)->create();
    }
}
