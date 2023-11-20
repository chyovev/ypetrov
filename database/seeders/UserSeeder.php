<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $this->createPrimaryUser();
        $this->createOtherUsers();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * It is essential that the primary user has a known username
     * and password in order to guarantee access to the CMS to
     * developers.
     * 
     * NB! Since there's a unique constraint on the email column,
     *     but the seeder can be executed multiple times, it's
     *     wise to insert said primary user only the first time
     *     around.
     */
    private function createPrimaryUser(): void {
        $name     = 'Administrator';
        $email    = 'email@example.com';
        $password = 'password';

        if ( ! User::where('email', $email)->exists()) {
            User::factory()->create([
                'name'     => $name,
                'email'    => $email,
                'password' => $password, // password gets hashed automatically
            ]);
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Create two more users with faker data.
     */
    private function createOtherUsers(): void {
        User::factory(2)->create();
    }
}
