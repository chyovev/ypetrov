<?php

namespace Database\Seeders;

/**
 * Since the static pages are... well, static,
 * their seeder should not create new records
 * over and over again like the rest of the
 * seeders do.
 * Instead, it should only verify that the
 * records exist – if they don't, create them;
 * if they do, don't do anything.
 */

use App\Models\StaticPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{

    /**
     * Even though the static pages cannot simply
     * be deleted, for consistency reasons the
     * WithoutModelEvents trait will still be used
     * since there are observers taking care of
     * polymorphic relationships (such as attachments).
     * 
     * @see \App\Models\Traits\HasAttachments :: initializeHasComments()
     */
    use WithoutModelEvents;

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $ids = $this->getStaticPagesIds();

        foreach ($ids as $id) {
            if ( ! $this->staticPageExists($id)) {
                $this->createStaticPage($id);
            }
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getStaticPagesIds(): array {
        return [
            StaticPage::BIOGRAPHY_ID,
            StaticPage::CHRESTOMATHY_ID,
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function staticPageExists(int $id): bool {
        return StaticPage::where('id', $id)->exists();
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createStaticPage(int $id) {
        StaticPage::factory()->create(['id' => $id]);
    }
}
