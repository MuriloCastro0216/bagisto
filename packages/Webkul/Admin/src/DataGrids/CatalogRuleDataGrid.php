<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CatalogRuleDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('catalog_rules')
            ->addSelect('catalog_rules.id', 'name', 'status', 'starts_from', 'ends_till', 'sort_order');

        // $this->addFilter('status', 'status');
        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'starts_from',
            'label'      => trans('admin::app.datagrid.start'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
            'closure'    => function ($value) {
                return $value->starts_from ?? '-';
            },
        ]);

        $this->addColumn([
            'index'      => 'ends_till',
            'label'      => trans('admin::app.datagrid.end'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
            'closure'    => function ($value) {
                return $value->ends_till ?? '-';
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.status'),
            'type'       => 'boolean',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($value) {
                if ($value->status) {
                    return trans('admin::app.datagrid.active');
                }

                return trans('admin::app.datagrid.inactive');
            },
        ]);

        $this->addColumn([
            'index'      => 'sort_order',
            'label'      => trans('admin::app.datagrid.priority'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.catalog_rules.edit',
            'url'          => function ($row) {
                return route('admin.catalog_rules.edit', $row->id);
            },
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'DELETE',
            'route'  => 'admin.catalog_rules.delete',
            'url'          => function ($row) {
                return route('admin.catalog_rules.delete', $row->id);
            },
        ]);
    }
}
