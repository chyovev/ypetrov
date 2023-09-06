<?php

namespace Database\Seeders;

use App\Models\Poem;
use Illuminate\Database\Seeder;

class PoemSeeder extends Seeder
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Poem::factory(100)->create();
    }
}
