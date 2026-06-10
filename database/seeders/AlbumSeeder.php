<?php

namespace Database\Seeders;

use App\Models\Album;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlbumSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Album::factory(30)->create();
    }
}
