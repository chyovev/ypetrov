<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{

    /**
     * There is a dynamic polymorphic observer listening
     * for delete event on Commentable models (such as Video),
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
        Video::factory(5)->create();
    }
}
