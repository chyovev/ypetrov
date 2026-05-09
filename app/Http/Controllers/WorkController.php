<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Poem;
use Illuminate\View\View;

class WorkController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    public function get_book(Book $book): View {
        $book->addImpression();

        $data = [
            'book'   => $book,
            'seo'    => $book,
            'images' => $book->getImages(),
        ];

        return view('public.works.book', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function get_poem(Book $book, Poem $poem): View {
        $poem->addImpression();

        $data = [
            'book'   => $book,
            'poem'   => $poem,
            'seo'    => $poem,
            'images' => $book->getImages(),
        ];

        return view('public.works.poem', $data);
    }

}
