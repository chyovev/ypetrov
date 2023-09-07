<?php

namespace Database\Seeders;

use App\Models\PressArticle;
use Illuminate\Database\Seeder;

class PressArticleSeeder extends Seeder
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        PressArticle::factory(5)->create();
    }
}
