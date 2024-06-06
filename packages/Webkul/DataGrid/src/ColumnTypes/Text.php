<?php

namespace Webkul\DataGrid\ColumnTypes;

use Webkul\DataGrid\Column;
use Webkul\DataGrid\Enums\FilterTypeEnum;

class Text extends Column
{
    /**
     * Process filter.
     */
    public function processFilter($queryBuilder, $requestedValues)
    {
        if ($this->filterableType === FilterTypeEnum::DROPDOWN->value) {
            return $queryBuilder->where(function ($scopeQueryBuilder) use ($requestedValues) {
                foreach ($requestedValues as $value) {
                    $scopeQueryBuilder->orWhere($this->getDatabaseColumnName(), $value);
                }
            });
        }

        return $queryBuilder->where(function ($scopeQueryBuilder) use ($requestedValues) {
            foreach ($requestedValues as $value) {
                $scopeQueryBuilder->orWhere($this->getDatabaseColumnName(), 'LIKE', '%'.$value.'%');
            }
        });
    }
}
