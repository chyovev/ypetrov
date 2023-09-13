<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GalleryImageSeeder extends Seeder
{

    /**
     * There is a dynamic polymorphic observer listening
     * for delete event on Attachable models (such as GalleryImage),
     * but it should not be fired during seeding.
     * 
     * @see \App\Models\Traits\HasAttachments :: initializeHasAttachments()
     */
    use WithoutModelEvents;

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        GalleryImage::factory(20)->create();
    }
}
