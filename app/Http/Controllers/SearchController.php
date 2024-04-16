<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;

class SearchController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Find all poems which match a searched string.
     * 
     * @param SearchRequest $request
     */
    public function index(SearchRequest $request) {
        $request->process();
        
        $data = [
            'results' => $request->getResults(),
            'books'   => $request->getBooks(),
            'grouped' => $request->getResultsGroupedByBooks(),
            'search'  => $request->getSearchString(),
            'noindex' => true,
            'seo'     => seo($request->getMetaTitle()),
        ];

        return view('public.search.index', $data);
    }
    
}
