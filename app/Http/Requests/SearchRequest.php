<?php

namespace App\Http\Requests;

use App\Models\Book;
use App\Models\Poem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SearchRequest extends FormRequest
{

    /**
     * All matched poems.
     * Gets set in the process() method.
     * 
     * @var LengthAwarePaginator
     */
    private $results;

    ///////////////////////////////////////////////////////////////////////////
    public function rules() {
        return [
            's' => 'sometimes|string',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Find all poems which match the searched string and
     * save them under the resuls property.
     * 
     * @return void
     */
    public function process(): void {
        $this->results = Poem::query()
            ->fullyActive()
            ->with(['books' => function($query) {
                $query->active();
            }])
            ->whereFullText(['title', 'dedication', 'text'], $this->getSearchString())
            ->paginate();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the search string from the respective query parameter (if any).
     * 
     * @return string|null
     */
    public function getSearchString(): ?string {
        return $this->validated('s');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get all matched poems in a paginated manner.
     * 
     * @return LengthAwarePaginator
     */
    public function getResults(): LengthAwarePaginator {
        return $this->results;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Group results by the first active book: if multiple poems
     * from are part of the same book, they should be listed
     * under its hood.
     * 
     * @return Collection
     */
    public function getResultsGroupedByBooks(): Collection {
        return collect($this->results->items())->groupBy('books.0.id');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Cycle through the results and extract a distinct collection of all books.
     * 
     * @return Collection<Book>
     */
    public function getBooks(): Collection {
        $books = [];

        foreach ($this->results as $item) {
            $book   = $item->books->first();
            $bookId = $book->id;

            if (array_key_exists($bookId, $books)) {
                continue;
            }

            $books[$bookId] = $book;
        }

        return collect($books);
    }
}