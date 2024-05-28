<?php

namespace Webkul\DataGrid\Enums;

enum ColumnTypeEnum: string
{
    /**
     * String.
     */
    case STRING = 'string';

    /**
     * Integer.
     */
    case INTEGER = 'integer';

    /**
     * Aggregate.
     */
    case AGGREGATE = 'aggregate';

    /**
     * Boolean.
     */
    case BOOLEAN = 'boolean';

    /**
     * Dropdown.
     */
    case DROPDOWN = 'dropdown';

    /**
     * Date range.
     */
    case DATE_RANGE = 'date_range';

    /**
     * Date time range.
     */
    case DATE_TIME_RANGE = 'datetime_range';
}
