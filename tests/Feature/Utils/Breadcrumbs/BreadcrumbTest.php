<?php

namespace Tests\Feature\Utils\Breadcrumbs;

use App\Utils\Breadcrumbs\Breadcrumb;
use Tests\TestCase;

class BreadcrumbTest extends TestCase
{

    ///////////////////////////////////////////////////////////////////////////
    public function test_getters(): void {
        $parent = (new Breadcrumb('parent1'))
            ->title('Parent crumb')
            ->url(url('/'))
            ->child('child1', function(Breadcrumb $crumb): void {
                $crumb
                    ->title('Child crumb')
                    ->url('https://example.com');
            });

        $this->assertSame('parent1', $parent->getName());
        $this->assertNull($parent->getParent());
        $this->assertSame(1, count($parent->getChildren()));
        $this->assertSame('Parent crumb', $parent->getTitle(null));
        $this->assertSame('http://ypetrov.localhost', $parent->getUrl(null));

        $child = $parent->getChildren()[0];
        $this->assertSame($parent, $child->getParent());
        $this->assertSame(0, count($child->getChildren()));
        $this->assertSame('Child crumb', $child->getTitle(null));
        $this->assertSame('https://example.com', $child->getUrl(null));
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_callback_getters(): void {
        $parent = (new Breadcrumb('test'))
            ->title(function(int $param): string {
                return "Prefix #{$param}";
            })
            ->url(function(int $param): string {
                return route('public.home', ['test' => $param]);
            });

        $this->assertSame('Prefix #1', $parent->getTitle(1));
        $this->assertSame('http://ypetrov.localhost?test=1', $parent->getUrl(1));
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_orphan_getters(): void {
        $crumb = new Breadcrumb('test');

        $this->assertSame('test', $crumb->getName());
        $this->assertNull($crumb->getParent());
        $this->assertSame(0, count($crumb->getChildren()));
        $this->assertNull($crumb->getTitle(null));
        $this->assertNull($crumb->getUrl(null));
    }

}