<?php

namespace App\Admin\Http\Controllers;

use App\Models\Book;
use App\Models\Poem;
use App\Http\Controllers\Controller;
use App\Admin\Http\Requests\Books\FormRequest;
use Illuminate\Database\Eloquent\Collection;

class BookController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $query = Book::query()
            ->orderBy('order', 'asc')
            ->withCount('poems');

        return view('admin.books.index', [
            'books' => $query->paginate(20),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $poems = $this->getAllPoems();

        return view('admin.books.form', [
            'book'  => new Book(),
            'poems' => $poems,
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch all poems from the database so the user
     * can choose which of them should be included
     * in a single book.
     * 
     * @return Collection<Poem>
     */
    private function getAllPoems(): Collection {
        return Poem::query()
            ->orderBy('title', 'asc')
            ->get();
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * Store a newly created resource in storage.
     */
    public function store(FormRequest $request) {
        $data = $request->validated();

        $book = Book::create($data);

        $book->syncPoemsInOrder($request->input('poems'));

        return redirect()
            ->route('admin.books.edit', ['book' => $book])
            ->withSuccess('Book successfully created!');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book) {
        $poems = $this->getAllPoems();

        return view('admin.books.form', [
            'book'  => $book,
            'poems' => $poems,
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Update the specified resource in storage.
     */
    public function update(FormRequest $request, Book $book) {
        $data = $request->validated();

        $book->update($data);

        $book->syncPoemsInOrder($request->input('poems'));

        return redirect()
            ->route('admin.books.edit', ['book' => $book])
            ->withSuccess('Book successfully updated!');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Remove the specified resource from storage.
     * 
     * NB! Keep in mind that Book objects have polymorphic relationships
     *     which cannot have cascade deletion in MySQL. To work around this
     *     problem, the Book class implements polymorphic interfaces which
     *     have registered delete observers.
     */
    public function destroy(Book $book) {
        $book->delete();

        return redirect()
            ->back()
            ->withSuccess('Book successfully deleted!');
    }

}