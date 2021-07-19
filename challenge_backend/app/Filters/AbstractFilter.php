<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter {

    protected $request;

    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function filter(Builder $builder)
    {
        foreach ($this->getFilters() as $filter => $value)
        {
            $this->resolveFilter($filter)->filter($builder, $value);
        }
    }

    public function getFilters()
    {
        return array_filter($this->request->only(array_keys($this->filters)));
    }

    // calls the type class that matches the value inside the fitters array which have a key (type) and a value equals to the class
    public function resolveFilter($filter)
    {
        return new $this->filters[$filter];
    }

}
