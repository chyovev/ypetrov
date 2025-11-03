<?php

namespace Database\Seeders;

use LogicException;
use App\Models\ContactMessage;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

/**
 * ContactMessageSeeder is dependent on Visito records.
 * Make sure the Visitor seeder is ran beforehand.
 */

class ContactMessageSeeder extends Seeder
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $visitors = $this->getVisitors();

        for ($i = 1; $i <= 5; $i++) {
            $visitor = $visitors->random();

            ContactMessage::factory()->for($visitor)->create();
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get all visitors to choose random for association records.
     * If no visitors are found, an exception will be thrown.
     * 
     * @throws LogicException – empty collection of visitors
     * @return Collection<Visitor>
     */
    private function getVisitors(): Collection {
        $visitors = Visitor::all();

        if ( ! $visitors->count()) {
            throw new LogicException('Missing visitor records. Try running the visitor seeder first.');
        }

        return $visitors;
    }
}
