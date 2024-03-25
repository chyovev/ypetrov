<?php

namespace App\Admin\Http\Requests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * If there's a search query parameter passed to the request,
     * apply it to the query in order to filter down results.
     * 
     * @param Builder $query   – query being prepared
     * @param string[] $fields – searchable fields 
     */
    public function addOptionalFilterToQuery(Builder $query, array $fields): void {
        $filter = $this->query('search');

        if (is_null($filter)) {
            return;
        }

        // the searchable fields are listed as OR statements,
        // but they should all be wrapped in another where
        // clause in order to encapsulate them from other
        // conditions applied to the query
        $query->where(function($q) use ($fields, $filter) {
            foreach ($fields as $field) {
                $q->orWhere($field, 'LIKE', "%{$filter}%");
            }
        });
    }

}
