<?php

namespace App\Filters;

use App\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class CustomerFilter extends AbstractFilter
{

    // filter array

    protected $filters = [
        'type' => TypeFilter::class,
        'id' => IdFilter::class,
        'name' => FirstNameFilter::class,
        'email' => EmailFilter::class,
    ];


}
