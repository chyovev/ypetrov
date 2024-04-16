<?php

namespace App\Helpers;

/**
 * Helper class used to add meta data to non-dynamic pages.
 * Use the helper seo() function to initialize this object.
 */

class SEO
{

    /**
     * Custom non-dynamic meta title.
     * 
     * @var string
     */
    private ?string $metaTitle;

    /**
     * Custom non-dynamic meta description.
     * 
     * @var string
     */
    private ?string $metaDescription;


    ///////////////////////////////////////////////////////////////////////////
    public function __construct(string $metaTitle, string $metaDescription = null) {
        $this->metaTitle       = $metaTitle;
        $this->metaDescription = $metaDescription;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaTitle(): ?string {
        return $this->metaTitle;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaDescription(): ?string {
        return $this->metaDescription;
    }

}