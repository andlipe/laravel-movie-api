<?php

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenderTableSeeder extends Seeder
{

    public function run()
    {
        factory(Genre::class, 100)->create();
    }
}
