<?php

namespace Database\Seeders;

use LogicException;
use App\Models\Stats;
use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

/**
 * Unlike most of the other seeders which simply generate
 * new records using the respective factories, the
 * Stats seeder creates records *associated*
 * with some of the existing records of the public models.
 * Therefore, it is a pre-condition to have the seeders
 * of the public models executed beforehand, otherwise
 * an exception will be thrown. 
 * 
 * @see \Database\Seeders\DatabaseSeeder
 */

class StatsSeeder extends Seeder
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $videos = $this->getVideos();
        
        foreach ($videos as $video) {
            Stats::factory()->for($video, 'statsable')->create();
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get 3 random videos in order to create stats records for them.
     * If no videos are found, an exception will be thrown.
     * 
     * @throws LogicException – empty collection of videos
     * @return Collection<Video>
     */
    private function getVideos(): Collection {
        $videos = Video::query()
            ->orderByRaw('RAND()')
            ->limit(10)
            ->get();
        
        if ( ! $videos->count()) {
            throw new LogicException('Missing video records. Try running the video seeder first.');
        }

        return $videos;
    }
}
