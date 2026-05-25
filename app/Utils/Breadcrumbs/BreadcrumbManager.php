<?php

namespace App\Utils\Breadcrumbs;

use LogicException;

class BreadcrumbManager
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private AdminCrumbs $crumbs) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * @throws LogicException
     */
    public function getCrumbs(string $name, mixed $arg = null): array {
        $match = $this->getBreadcrumbByName($this->crumbs->get(), $name);

        if (is_null($match)) {
            throw new LogicException("No matching breadcrumb found for '{$name}'");
        }

        return $this->convertToLinks($match, $arg);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getBreadcrumbByName(Breadcrumb $parent, string $name): ?Breadcrumb {
        foreach ($parent->getChildren() as $crumb) {
            if ($match = $this->getBreadcrumbByName($crumb, $name)) {
                return $match;
            }
        }

        if ($parent->getName() === $name) {
            return $parent;
        }

        return null;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function convertToLinks(Breadcrumb $crumb, mixed $arg): array {
        $links = [];

        while ($crumb) {
            $links[] = new Link(
                $crumb->getTitle($arg),
                $crumb->getUrl($arg),
            );

            $crumb = $crumb->getParent();
        }

        return array_reverse($links);
    }

}