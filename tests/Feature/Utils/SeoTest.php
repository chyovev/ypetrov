<?php

namespace Tests\Feature\Utils;

use App\Utils\Seo;
use Tests\TestCase;

class SeoTest extends TestCase
{

    ///////////////////////////////////////////////////////////////////////////
    public function test_no_parameters_default_values(): void {
        $seo = new Seo;
        
        $this->assertSame('Официален сайт в памет на поета Йосиф Петров (1909 – 2004)', $seo->getMetaTitle());
        $this->assertSame('Йосиф Петров е български поет, общественик и политик', $seo->getMetaDescription());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_null_parameters_default_value(): void {
        $seo = new Seo(null, null);
        
        $this->assertSame('Официален сайт в памет на поета Йосиф Петров (1909 – 2004)', $seo->getMetaTitle());
        $this->assertSame('Йосиф Петров е български поет, общественик и политик', $seo->getMetaDescription());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_title_gets_suffix(): void {
        $seo = new Seo('Test');
        
        $this->assertSame('Test | Йосиф Петров (1909 – 2004)', $seo->getMetaTitle());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_html_tags_stripped(): void {
        $seo = new Seo('Hello <strong>world</strong>', '<div>Test</div>');
        
        $this->assertSame('Hello world | Йосиф Петров (1909 – 2004)', $seo->getMetaTitle());
        $this->assertSame('Test', $seo->getMetaDescription());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_white_spaces_trimmed(): void {
        $seo = new Seo(' Hello     world   ', " with \n new lines");
        
        $this->assertSame('Hello world | Йосиф Петров (1909 – 2004)', $seo->getMetaTitle());
        $this->assertSame('with new lines', $seo->getMetaDescription());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_description_trimmed_to_first_50_words(): void {
        $seo = new Seo(null, str_repeat(' test', 100));

        $this->assertSame(50, str_word_count($seo->getMetaDescription()));
        $this->assertStringEndsWith('...', $seo->getMetaDescription());
    }
}