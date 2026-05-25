<?php

namespace Tests\Feature\Utils\Breadcrumbs;

use App\Models\Poem;
use App\Utils\Breadcrumbs\AdminCrumbs;
use App\Utils\Breadcrumbs\Breadcrumb;
use App\Utils\Breadcrumbs\BreadcrumbManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use LogicException;
use Mockery\MockInterface;
use Tests\TestCase;

class BreadcrumbManagerTest extends TestCase
{

    use DatabaseTransactions;

    ///////////////////////////////////////////////////////////////////////////
    public function test_get_root_crumb(): void {
        $crumb = (new Breadcrumb('test'))
            ->title('Test')
            ->url(route('home'));

        $this->mock(AdminCrumbs::class, function(MockInterface $mock) use($crumb): void {
            $mock->shouldReceive('get')->andReturn($crumb);
        });

        $manager = $this->app->make(BreadcrumbManager::class);
        $links   = $manager->getCrumbs('test');

        $this->assertSame(1, count($links));
        $this->assertSame('Test', $links[0]->getTitle());
        $this->assertSame('http://ypetrov.localhost', $links[0]->getUrl());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_get_nested_crumb(): void {
        $manager = $this->app->make(BreadcrumbManager::class);
        $links   = $manager->getCrumbs('users.create');

        $this->assertSame(3, count($links));
        $this->assertSame('Начало', $links[0]->getTitle());
        $this->assertSame('http://ypetrov.localhost/admin', $links[0]->getUrl());
        $this->assertSame('Потребители', $links[1]->getTitle());
        $this->assertSame('http://ypetrov.localhost/admin/users', $links[1]->getUrl());
        $this->assertSame('Добавяне на запис', $links[2]->getTitle());
        $this->assertSame('http://ypetrov.localhost/admin/users/create', $links[2]->getUrl());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_get_nested_crumb_with_parameter(): void {
        $poem    = Poem::factory()->create();
        $manager = $this->app->make(BreadcrumbManager::class);
        $links   = $manager->getCrumbs('poems.edit', $poem);

        $this->assertSame(4, count($links));
        $this->assertSame('Начало', $links[0]->getTitle());
        $this->assertSame('http://ypetrov.localhost/admin', $links[0]->getUrl());
        $this->assertSame('Творчество', $links[1]->getTitle());
        $this->assertSame(null, $links[1]->getUrl());
        $this->assertSame('Стихотворения', $links[2]->getTitle());
        $this->assertSame('http://ypetrov.localhost/admin/poems', $links[2]->getUrl());
        $this->assertSame($poem->title, $links[3]->getTitle());
        $this->assertSame("http://ypetrov.localhost/admin/poems/{$poem->id}/edit", $links[3]->getUrl());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_get_non_existing_crumb(): void {
        $manager = $this->app->make(BreadcrumbManager::class);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("No matching breadcrumb found for 'test'");
        
        $manager->getCrumbs('test');
    }
}