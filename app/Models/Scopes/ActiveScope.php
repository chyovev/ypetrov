<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveScope implements Scope
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Global query scope to filter only active records.
     * 
     * NB! The scope is targetted at the public part of the project
     *     where inactive records should remain hidden, but they
     *     should still be accessible from the administrative panel.
     *     Whether the scope is used on the public or administrative
     *     part can be determined by the current request, but scopes
     *     don't work with dependency injection, so the request (if
     *     any) is injected in a hidden way.
     */
    public function apply(Builder $builder, Model $model): void {
        if ( ! request()?->routeIs('admin.*')) {
            $builder->active();
        }
    }

}
