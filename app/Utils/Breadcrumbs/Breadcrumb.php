<?php

namespace App\Utils\Breadcrumbs;

use Closure;

class Breadcrumb
{

    private ?Closure $titleCallback = null;
    private ?Closure $urlCallback = null;

    /**
     * @var Breadcrumb[]
     */
    private array $children = [];

    private ?Breadcrumb $parent = null;

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private string $name) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getName(): string {
        return $this->name;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * @param string|Closure $title
     */
    public function title($title): self {
        $this->titleCallback = ($title instanceof Closure)
            ? $title
            : fn(): string => $title;

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getTitle(mixed $arg): ?string {
        if (is_null($this->titleCallback)) {
            return null;
        }

        return call_user_func($this->titleCallback, $arg);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * @param string|Closure $url
     */
    public function url($url): self {
        $this->urlCallback = ($url instanceof Closure)
            ? $url
            : fn(): string => $url;

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getUrl(mixed $arg): ?string {
        if (is_null($this->urlCallback)) {
            return null;
        }
        
        return call_user_func($this->urlCallback, $arg);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function child(string $name, Closure $callback): self {
        $crumb = (new self($name))->parent($this);

        call_user_func($callback, $crumb);

        $this->children[] = $crumb;

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function parent(Breadcrumb $parent): self {
        $this->parent = $parent;

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * @return Breadcrumb[]
     */
    public function getChildren(): array {
        return $this->children;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getParent(): ?Breadcrumb {
        return $this->parent;
    }

}