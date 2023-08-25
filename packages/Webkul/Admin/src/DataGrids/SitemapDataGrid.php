<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\DataGrid\DataGrid;

class SitemapDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('sitemaps')->addSelect('id', 'file_name', 'path', 'path as url');

        return $queryBuilder;
    }

    /**
     * Add Columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.marketing.sitemaps.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'width'      => '40px',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'file_name',
            'label'      => trans('admin::app.marketing.sitemaps.index.datagrid.file-name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'path',
            'label'      => trans('admin::app.marketing.sitemaps.index.datagrid.path'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'url',
            'label'      => trans('admin::app.marketing.sitemaps.index.datagrid.link-for-google'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
            'closure'    => function ($row) {
                return Storage::url($row->path . '/' . $row->file_name);
            },
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
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.marketing.sitemaps.index.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.sitemaps.update',
            'url'    => function ($row) {
                return route('admin.sitemaps.update', $row->id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.marketing.sitemaps.index.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => function ($row) {
                return route('admin.sitemaps.delete', $row->id);
            },
        ]);
    }
}
