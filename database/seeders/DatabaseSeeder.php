<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Seed the application's database.
     */
    public function run(): void {
        $this->call([
            VisitorSeeder::class,
            UserSeeder::class,
            StaticPageSeeder::class,
            BookSeeder::class,
            PoemSeeder::class,
            BookPoemSeeder::class,
            PressArticleSeeder::class,
            VideoSeeder::class,
            GalleryImageSeeder::class,
            EssaySeeder::class,
            ContactMessageSeeder::class,
            CommentSeeder::class,
            AttachmentSeeder::class,
            StatsSeeder::class,
            LikeSeeder::class,
            ImpressionSeeder::class,
        ]);
    }
}
