<?php

namespace App\View\ViewModels;

use App\Models\Book;
use App\Models\Poem;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SearchViewModel
{

    /**
     * @var Collection<string>
     */
    private Collection $searchWords;

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(
        private readonly ?string $search,
        private readonly LengthAwarePaginator $data
    ) {
        $this->searchWords = $this->toWords();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Split the search string into a collection of unique words.
     * The words should be ordered from longest to shortest –
     * extracting a match for a longer word is typically
     * more useful than a simple preposition match.
     * Additionally, use the whole search string as an initial
     * element – if there's a match in its entirety, that would
     * be even better. 
     * 
     * @return Collection<string>
     */
    private function toWords(): Collection {
        // pattern matches anything but:
        //      \p{L} = any kind of letter from any language
        //      \p{N} = any kind of numeric character in any script
        $pattern = '/[^\p{L}\p{N}]+/u';

        return str($this->search)
            ->split($pattern)
            ->sortBy(function(string $word): int {
                return mb_strlen($word);
            })
            ->reverse()
            ->prepend($this->search)
            ->filter()
            ->unique();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Results apply to poems only, but for better UX said poems
     * should be grouped by the first book they appeared in.
     */
    public function getData(): Collection {
        return $this
            ->getBooks()
            ->map(function(Book $book): Collection {
                return $this->mapBook($book);
            });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getBooks(): Collection {
        return $this
            ->data
            ->getCollection()
            ->map(function(Poem $poem): Book {
                return $poem->books->first();
            })
            ->unique();
    }

    ///////////////////////////////////////////////////////////////////////////
    private function mapBook(Book $book): Collection {
        return collect([
            'slug'         => $book->slug,
            'title'        => $book->title,
            'publish_year' => $book->publish_year,
            'cover_image'  => $book->getCoverImage(),
            'poems'        => $this->mapPoemsOfBook($book),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function mapPoemsOfBook(Book $book): Collection {
        return $this
            ->data
            ->getCollection()
            ->filter(function(Poem $poem) use($book): bool {
                return $poem->books->first()->id === $book->id;
            })
            ->map(function(Poem $poem): Collection {
                return $this->mapPoem($poem);
            });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function mapPoem(Poem $poem): Collection {
        return collect([
            'slug'       => $poem->slug,
            'title'      => $this->highlight($poem->title),
            'dedication' => $this->highlight($poem->dedication),
            'text'       => $this->highlight($poem->text),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Highlight all words from the search string
     * which are present in the text excerpt.
     */
    private function highlight(?string $text): ?string {
        if (is_null($text)) {
            return null;
        }

        // to strip HTML tags and new lines for more accurate results
        $text = preg_replace('/\s+/', ' ', strip_tags($text));

        $excerpt = $this->extractExcerpt($text);

        return preg_replace("/({$this->searchWords->implode('|')})/ui", '<strong>$1</strong>', $excerpt);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Split a text into a collection of words and extract
     * an excerpt containing the longest one (longer words
     * usually are better for context match than prepositions).
     */
    private function extractExcerpt(string $text): string {
        foreach ($this->searchWords as $word) {
            if (str($text)->contains($word, true)) {
                return str($text)->excerpt($word);
            }
        }

        // if no match was found, show the first 40 words
        return str($text)->words(40);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function total(): int {
        return $this->data->total();
    }

    ///////////////////////////////////////////////////////////////////////////
    public function from(): int {
        return $this->data->firstItem();
    }

    ///////////////////////////////////////////////////////////////////////////
    public function to(): int {
        return $this->data->lastItem();
    }

    ///////////////////////////////////////////////////////////////////////////
    public function generatePagination(): Htmlable {
        return $this->data->withQueryString()->links();
    }

}