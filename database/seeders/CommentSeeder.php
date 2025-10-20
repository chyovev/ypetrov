<?php

namespace Database\Seeders;

use LogicException;
use App\Models\Comment;
use App\Models\Poem;
use App\Models\Interfaces\Commentable;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;

/**
 * Unlike some of the other seeders which simply generate
 * new records using the respective factories, the Comment
 * seeder creates comments *associated* with some of the
 * existing records of the commentable models & visitor.
 * Therefore, it is a pre-condition to have the seeders
 * of the commentable models executed beforehand, otherwise
 * an exception will be thrown. 
 * 
 * @see \Database\Seeders\DatabaseSeeder
 */

class CommentSeeder extends Seeder
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $poems    = $this->getPoems();
        $visitors = $this->getVisitors();
        
        foreach ($poems as $poem) {
            $visitor = $visitors->random();

            $this->createRegularComments($poem, $visitor, 3);
            $this->createDeletedComments($poem, $visitor, 1);
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get 10 random poems in order to create comments for them.
     * If no poems are found, an exception will be thrown.
     * 
     * @throws LogicException – empty collection of poems
     * @return Collection<Commentable>
     */
    private function getPoems(): Collection {
        $poems = Poem::query()
            ->orderByRaw('RAND()')
            ->limit(10)
            ->get();
        
        if ( ! $poems->count()) {
            throw new LogicException('Missing poem records. Try running the poem seeder first.');
        }

        return $poems;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get 5 random visitors to associate comment records with.
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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Create regular Comment records.
     * 
     * @param  Commentable $object – main object
     * @param  Visitor $visitor    – association object
     * @param  int $count – how many records to create, 1 by default
     * @return Collection<Comment>
     */
    private function createRegularComments(Commentable $object, Visitor $visitor, int $count = 1): Collection {
        return $this
            ->getFactory($object, $visitor)
            ->count($count)
            ->create();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Due to the SoftDeletes trait used by the Comment model
     * comments can be marked as deleted using the trashed()
     * factory method.
     * 
     * @param  Commentable $object – main object
     * @param  Visitor $visitor    – association object 
     * @param  int $count – how many records to create, 1 by default
     * @return Collection<Comment>
     */
    private function createDeletedComments(Commentable $object, Visitor $visitor, int $count = 1): Collection {
        return $this
            ->getFactory($object, $visitor)
            ->count($count)
            ->trashed()
            ->create();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get a factory instance for the Comment model
     * and associated it with a commentable object.
     * 
     * @param  Commentable $object – main object
     * @param  Visitor $visitor    – association object
     * @return Factory
     */
    private function getFactory(Commentable $object, Visitor $visitor): Factory {
        return Comment::factory()->for($object, 'commentable')->for($visitor);
    }

}
