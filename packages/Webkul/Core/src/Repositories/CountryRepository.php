<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Eloquent\Repository;

class CountryRepository extends Repository
{
    /**
     * @var boolean
     */
    protected $cacheEnabled = true;

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Core\Contracts\Country';
    }
}