<?php

namespace Database\Seeders;

use LogicException;
use App\Models\Attachment;
use App\Models\PressArticle;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;

/**
 * Unlike most of the other seeders which simply generate
 * new records using the respective factories, the Attachment
 * seeder creates attachments *associated* with some of the
 * existing records of the attachable models.
 * Therefore, it is a pre-condition to have the seeders
 * of the attachable models executed beforehand, otherwise
 * an exception will be thrown. 
 * 
 * @see \Database\Seeders\DatabaseSeeder
 */

class AttachmentSeeder extends Seeder
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $pressArticles = $this->getPressArticles();
        
        foreach ($pressArticles as $article) {
            Attachment::factory(2)->for($article, 'attachable')->create();
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get 10 random press articles in order to create attachments for them.
     * If no articles are found, an exception will be thrown.
     * 
     * @throws LogicException – empty collection of press articles
     * @return Collection<PressArticle>
     */
    private function getPressArticles(): Collection {
        $pressArticles = PressArticle::query()
            ->orderByRaw('RAND()')
            ->limit(10)
            ->get();
        
        if ( ! $pressArticles->count()) {
            throw new LogicException('Missing press article records. Try running the press article seeder first.');
        }

        return $pressArticles;
    }

}
