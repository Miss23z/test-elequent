<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Genre::factory(15)->create();
    }
}
