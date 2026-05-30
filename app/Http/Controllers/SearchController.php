<?php

namespace App\Http\Controllers;

use App\Utils\Seo;
use App\Http\Requests\SearchRequest;
use App\View\ViewModels\SearchViewModel;

class SearchController
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Find all poems which match a searched string.
     * 
     * @param SearchRequest $request
     */
    public function index(SearchRequest $request) {
        $results = $request->process();
        
        $data = [
            'search'  => $request->getSearchString(),
            'results' => new SearchViewModel($request->getSearchString(), $results),
            'noindex' => true,
            'seo'     => new Seo($request->getMetaTitle()),
        ];

        return view('public.search.index', $data);
    }
    
}
