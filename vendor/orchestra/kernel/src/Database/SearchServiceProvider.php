<?php

namespace Orchestra\Database;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;
use Laravie\QueryFilter\Searchable;
use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        QueryBuilder::macro('whereLike', static function ($attributes, string $searchTerm) {
            return (new Searchable($searchTerm, Arr::wrap($attributes)))->apply($this);
        });

        EloquentBuilder::macro('whereLike', static function ($attributes, string $searchTerm) {
            return (new Searchable($searchTerm, Arr::wrap($attributes)))->apply($this);
        });
    }
}
