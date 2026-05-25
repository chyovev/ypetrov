<?php

namespace Tests\Feature\Utils\Breadcrumbs;

use App\Utils\Breadcrumbs\AdminCrumbs;
use App\Utils\Breadcrumbs\Breadcrumb;
use Tests\TestCase;

class AdminCrumbsTest extends TestCase
{

    ///////////////////////////////////////////////////////////////////////////
    public function test_structure(): void {
        $crumb = $this->app->make(AdminCrumbs::class)->get();

        $this->assertSame('home', $crumb->getName());
        $this->assertSame(9, count($crumb->getchildren()));

        $names = [
            'home' => [
                'dashboard' => [],
                'static_pages.edit' => [],
                'users.index' => [
                    'users.create' => [],
                    'users.edit'   => [],
                ],
                'contact_messages.index' => [
                    'contact_messages.show' => [],
                ],
                'works' => [
                    'poems.index' => [
                        'poems.create' => [],
                        'poems.edit'   => [],
                    ],
                    'books.index' => [
                        'books.create' => [],
                        'books.edit'   => [],
                    ],
                ],
                'essays.index' => [
                    'essays.create' => [],
                    'essays.edit'   => [],
                ],
                'press_articles.index' => [
                    'press_articles.create' => [],
                    'press_articles.edit'   => [],
                ],
                'videos.index' => [
                    'videos.create' => [],
                    'videos.edit'   => [],
                ],
                'gallery_images.index' => [
                    'gallery_images.create' => [],
                    'gallery_images.edit'   => [],
                ],
            ]
        ];
        $this->assertSame($names, $this->getNestedNames($crumb));
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getNestedNames(Breadcrumb $crumb): array {
        $childrenNames = collect($crumb->getChildren())
            ->flatMap(function(Breadcrumb $child): array {
                return $this->getNestedNames($child);
            })
            ->toArray();

        return [
            $crumb->getName() => $childrenNames,
        ];
    }
}