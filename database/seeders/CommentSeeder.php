<?php

namespace Database\Seeders;

use LogicException;
use App\Models\Comment;
use App\Models\Poem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Unlike most of the other seeders which simply generate
 * new records using the respective factories, the Comment
 * seeder creates comments *associated* with some of the
 * existing records of the commentable models.
 * Therefore, it is a pre-condition to have the seeders
 * of the commentable models executed beforehand, otherwise
 * an exception will be thrown. 
 * 
 * @see \Database\Seeders\DatabaseSeeder
 */

class CommentSeeder extends Seeder
{

    /**
     * There is an observer listening for create event
     * on the Comment model, but it should not be fired
     * during the seeding of comments.
     * 
     * @see \App\Observers\CommentObserver
     */
    use WithoutModelEvents;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $poems = $this->getPoems();
        
        foreach ($poems as $poem) {
            $this->createRegularComments($poem, 3);
            $this->createDeletedComments($poem, 1);
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get 10 random poems in order to create comments for them.
     * If no poems are found, an exception will be thrown.
     * 
     * @throws LogicException – empty collection of poems
     * @return Collection
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
     * Create regular Comment records.
     * 
     * @param  Model $object – main object
     * @param  int $count – how many records to create, 1 by default
     * @return Collection<Comment>
     */
    private function createRegularComments(Model $object, int $count = 1): Collection {
        return $this
            ->getFactory($object)
            ->count($count)
            ->create();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Due to the SoftDeletes trait used by the Comment model
     * comments can be marked as deleted using the trashed()
     * factory method.
     * 
     * @param  Model $object – main object
     * @param  int $count – how many records to create, 1 by default
     * @return Collection<Comment>
     */
    private function createDeletedComments(Model $object, int $count = 1): Collection {
        return $this
            ->getFactory($object)
            ->count($count)
            ->trashed()
            ->create();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get a factory instance for the Comment model
     * and associated it with a commentable object.
     * 
     * @param  Model $object – main object
     * @return Factory
     */
    private function getFactory(Model $object): Factory {
        return Comment::factory()->for($object, 'commentable');
    }

}
