<?php

namespace Database\Seeders;

use App\Models\Holder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolderSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Holder::factory(5)->create();
    }
}
