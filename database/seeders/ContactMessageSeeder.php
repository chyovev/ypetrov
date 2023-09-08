<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{

    /**
     * There is an observer listening for create event
     * on the ContactMessage model, but it should not
     * be fired during the seeding of contact messages.
     * 
     * @see \App\Observers\ContactMessageObserver
     */
    use WithoutModelEvents;

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        ContactMessage::factory(5)->create();
    }
}
