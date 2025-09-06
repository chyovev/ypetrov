<?php

namespace App\Http\Requests;

use App\Models\Book;
use App\Models\Poem;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ViewErrorBag;

class SearchRequest extends FormRequest
{

    /**
     * All matched poems.
     * Gets set in the process() method.
     * 
     * @var LengthAwarePaginator
     */
    private $results;

    /**
     * Whether the validation has failed.
     * Gets toggled in the failedValidation() method.
     * 
     * @var bool
     */
    private bool $failedValidation = false;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * The search parameter is optional – if not passed, the user is
     * simply shown the search form (hence the 'sometimes' rule).
     * If it is passed, but it's empty, the ConvertEmptyStringsToNull
     * middleware will nullify it (hence the 'nullable' rule).
     * Finally, the search string should be at least 3 characters long:
     * the full text search does not work with shorter strings.
     */
    public function rules() {
        return [
            's' => ['sometimes', 'nullable', 'string', 'min:3'],
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Failed validation on a form request usually triggers an exception
     * to be thrown which is then caught by the exception handler.
     * In most this would result in a redirect containing error bags.
     * The search request however is a simple GET request – any errors
     * should be passed on to the view without the need of a redirect.
     * 
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator) {
        $validatorBag = $validator->getMessageBag();

        $errorBag = new ViewErrorBag();
        $errorBag->put('default', $validatorBag);

        view()->share('errors', $errorBag);

        $this->failedValidation = true;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Set result either by fetching data from the database
     * or by mocking an empty response.
     * 
     * @return void
     */
    public function process(): void {
        $this->results = $this->shouldProcessSearch()
            ? $this->fetchDataFromDatabase()
            : $this->mockEmptyResponse();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A search request should actually be processed if the
     * validation has passed and the search string is not empty.
     * 
     * @return bool
     */
    private function shouldProcessSearch(): bool {
        if ($this->failedValidation) {
            return false;
        }

        if (empty($this->getSearchString())) {
            return false;
        }

        return true;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the search string from the respective query parameter (if any).
     * 
     * @return string|null
     */
    public function getSearchString(): ?string {
        return $this->query('s');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * If no search parameter is passed to the request, fake an
     * empty response instead of executing a redundant SQL query.
     * 
     * @return LengthAwarePaginator
     */
    private function mockEmptyResponse(): LengthAwarePaginator {
        $items   = [];
        $total   = 0;
        $perPage = 1;

        return new LengthAwarePaginator($items, $total, $perPage);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch all fully active poems from the database which match
     * the searched string.
     * 
     * @return LengthAwarePaginator
     */
    private function fetchDataFromDatabase(): LengthAwarePaginator {
        return Poem::query()
            ->fullyActive()
            ->with(['books' => function($query) {
                $query->active();
            }])
            ->whereFullText(['title', 'dedication', 'text'], $this->getSearchString())
            ->paginate();
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

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaTitle(): string {
        $string = $this->getSearchString();

        return $string
            ? "Търсене за '{$string}'"
            : "Търсене";
    }
}