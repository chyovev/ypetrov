<?php

namespace Database\Seeders;

use App\Models\Essay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EssaySeeder extends Seeder
{

    /**
     * There is a dynamic polymorphic observer listening
     * for delete event on Commentable models (such as Essay),
     * but it should not be fired during seeding.
     * 
     * @see \App\Models\Traits\HasComments :: initializeHasComments()
     */
    use WithoutModelEvents;

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Essay::factory(5)->create();
    }
}
