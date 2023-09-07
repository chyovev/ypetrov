<?php

namespace Database\Seeders;

use App\Models\Essay;
use Illuminate\Database\Seeder;

class EssaySeeder extends Seeder
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Essay::factory(5)->create();
    }
}
