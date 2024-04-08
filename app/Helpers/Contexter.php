<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Contexter
{

    /**
     * The text which the contect should
     * be extracted from.
     * Gets set in constructor.
     * 
     * @var string
     */
    private string $text;

    /**
     * The search string which ideally will
     * be mentioned in the extracted context.
     * Gets set in constructor.
     * 
     * @var string
     */
    private string $search;

    /**
     * The search string split into words.
     * 
     * @var string[]
     */
    private array $words;


    ///////////////////////////////////////////////////////////////////////////
    public function __construct(string $text, string $search) {
        $this->text   = preg_replace('/\s+/', ' ', $text);
        $this->search = $search;

        $this->prepareWords();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Prepare the search string as an array of words in order
     * to look for a specific match in the main text property.
     * The words should be sorted by their length in a descending
     * order – extracting a match for a longer word is typically
     * more useful than a simple preposition match.
     * Additionally, use the whole search string as an initial
     * element – if there's a match in its entirety, that would
     * be even better. 
     * 
     * @return void
     */
    private function prepareWords(): void {
        // get array of words
        $words = splitIntoWords($this->search);

        // sort them by their length in a descending order
        $words = Arr::sortDesc($words, function($string) {
            return mb_strlen($string);
        });

        // prepend the original string as a first element
        $words = Arr::prepend($words, $this->search);

        // filter out duplicates
        $this->words = array_unique($words);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A context sample typically contains the longest word from
     * the search. If no such match is found, the next word is being
     * used and so on. If none of the words were matched, simply use
     * the beginning of the text as a sample.
     * 
     * @return string
     */
    public function extract(): string {
        foreach ($this->words as $word) {
            if (Str::contains($this->text, $word, true)) {
                return Str::excerpt($this->text, $word);
            }
        }

        // if no match was found, show the first 40 words
        return Str::words($this->text, 40);
    }

}