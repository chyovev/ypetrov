<?php

namespace Database\Seeders;

use LogicException;
use App\Models\Like;
use App\Models\Stats;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

/**
 * Unlike some of the other seeders which simply generate
 * new records using the respective factories, the
 * Like seeder creates records *associated* with Stats
 * records & visitor which is why its seeds should be executed
 * beforehand, otherwise an exception will be thrown. 
 * 
 * @see \Database\Seeders\DatabaseSeeder
 */

class LikeSeeder extends Seeder
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $objects  = 10;
        $stats    = $this->getStats($objects);
        $visitors = $this->getVisitors($objects);

        // associate all visitors and stats via likes
        foreach ($visitors as $visitor) {
            foreach ($stats as $item) {
                Like::factory()->for($visitor)->for($item)->create();
            }
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get X random stats records in order to create like records for them.
     * If no stats are found, an exception will be thrown.
     * 
     * @throws LogicException – empty collection of stats
     * @param  int $limit     – how many records to fetch
     * @return Collection<Stats>
     */
    private function getStats(int $limit): Collection {
        $stats = Stats::query()
            ->orderByRaw('RAND()')
            ->limit($limit)
            ->get();
        
        if ( ! $stats->count()) {
            throw new LogicException('Missing stats records. Try running the stats seeder first.');
        }

        return $stats;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get all visitors to choose random for association records.
     * If no visitors are found, an exception will be thrown.
     * 
     * @throws LogicException – empty collection of visitors
     * @param  int $limit     – how many records to fetch
     * @return Collection<Visitor>
     */
    private function getVisitors(int $limit): Collection {
        $visitors = Visitor::limit($limit)->get();

        if ( ! $visitors->count()) {
            throw new LogicException('Missing visitor records. Try running the visitor seeder first.');
        }

        return $visitors;
    }
}
