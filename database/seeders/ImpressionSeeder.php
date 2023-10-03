<?php

namespace Database\Seeders;

use LogicException;
use App\Models\Impression;
use App\Models\Stats;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

/**
 * Unlike most of the other seeders which simply generate
 * new records using the respective factories, the
 * Impression seeder creates records *associated* with Stats
 * records which is why its seeds should be executed
 * beforehand, otherwise an exception will be thrown. 
 * 
 * @see \Database\Seeders\DatabaseSeeder
 */

class ImpressionSeeder extends Seeder
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $stats = $this->getStats();
        
        foreach ($stats as $item) {
            Impression::factory(5)->for($item)->create();
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get 3 random stats records in order to create impression records for them.
     * If no stats are found, an exception will be thrown.
     * 
     * @throws LogicException – empty collection of stats
     * @return Collection<Stats>
     */
    private function getStats(): Collection {
        $stats = Stats::query()
            ->orderByRaw('RAND()')
            ->limit(10)
            ->get();
        
        if ( ! $stats->count()) {
            throw new LogicException('Missing stats records. Try running the stats seeder first.');
        }

        return $stats;
    }
}
