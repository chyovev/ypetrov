<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Poem;
use App\Models\Visitor;
use Illuminate\View\View;

class WorkController
{

    ///////////////////////////////////////////////////////////////////////////
    public function get_book(Book $book, Visitor $visitor): View {
        $book->addImpression($visitor);

        $data = [
            'book'   => $book,
            'seo'    => $book->getSeo(),
            'images' => $book->getImages(),
        ];

        return view('public.works.book', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function get_poem(Book $book, Poem $poem, Visitor $visitor): View {
        $poem->addImpression($visitor);

        $data = [
            'book'   => $book,
            'poem'   => $poem,
            'seo'    => $poem->getSeo(),
            'images' => $book->getImages(),
        ];

        return view('public.works.poem', $data);
    }

}
