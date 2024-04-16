<?php

namespace App\Models\Interfaces;

/**
 * All models which are publicly accessible
 * should implement the SEO interface which
 * requires them to provide some meta data.
 */

interface SEO
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get a meta title for the SEO object.
     * 
     * @return string|null
     */
    public function getMetaTitle(): ?string;

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get a meta description for the SEO object.
     * 
     * @return string|null
     */
    public function getMetaDescription(): ?string;

}