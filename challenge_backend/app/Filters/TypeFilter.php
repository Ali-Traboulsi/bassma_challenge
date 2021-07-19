<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class TypeFilter {

//    protected $filterKey;

    public function filter (Builder $builder, $value) {

        // takes query string as argument and returns filtered result
        return $builder->where('type', $value);

    }

}
