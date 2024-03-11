<?php

namespace Webkul\Shop\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\Order;

class OrderDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('orders')
            ->addSelect(
                'orders.id',
                'orders.increment_id',
                'orders.status',
                'orders.created_at',
                'orders.grand_total',
                'orders.order_currency_code'
            )
            ->where('customer_id', auth()->guard('customer')->user()->id);

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
            'index'      => 'increment_id',
            'label'      => trans('shop::app.customers.account.orders.order-id'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('shop::app.customers.account.orders.order-date'),
            'type'       => 'date_range',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'grand_total',
            'label'      => trans('shop::app.customers.account.orders.total'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                return core()->formatPrice($row->grand_total, $row->order_currency_code);
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('shop::app.customers.account.orders.status.title'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => [
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.processing'),
                            'value'  => Order::STATUS_PROCESSING,
                        ],
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.completed'),
                            'value'  => Order::STATUS_COMPLETED,
                        ],
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.canceled'),
                            'value'  => Order::STATUS_CANCELED,
                        ],
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.closed'),
                            'value'  => Order::STATUS_CLOSED,
                        ],
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.pending'),
                            'value'  => Order::STATUS_PENDING,
                        ],
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.pending-payment'),
                            'value'  => Order::STATUS_PENDING_PAYMENT,
                        ],
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.fraud'),
                            'value'  => Order::STATUS_FRAUD,
                        ],
                    ],
                ],
            ],
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case Order::STATUS_PROCESSING:
                        return '<p class="label-processing">'.trans('shop::app.customers.account.orders.status.options.processing').'</p>';

                    case Order::STATUS_COMPLETED:
                        return '<p class="label-active">'.trans('shop::app.customers.account.orders.status.options.completed').'</p>';

                    case Order::STATUS_CANCELED:
                        return '<p class="label-canceled">'.trans('shop::app.customers.account.orders.status.options.canceled').'</p>';

                    case Order::STATUS_CLOSED:
                        return '<p class="label-closed">'.trans('shop::app.customers.account.orders.status.options.closed').'</p>';

                    case Order::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('shop::app.customers.account.orders.status.options.pending').'</p>';

                    case Order::STATUS_PENDING_PAYMENT:
                        return '<p class="label-pending">'.trans('shop::app.customers.account.orders.status.options.pending-payment').'</p>';

                    case Order::STATUS_FRAUD:
                        return '<p class="label-canceled">'.trans('shop::app.customers.account.orders.status.options.fraud').'</p>';
                }
            },
        ]);
    }
    ->leftJoin('addresses as order_address_shipping', function ($leftJoin) {
        $leftJoin->on('order_address_shipping.order_id', '=', 'orders.id')
            ->where('order_address_shipping.address_type', OrderAddress::ADDRESS_TYPE_SHIPPING);
    })
    ->leftJoin('addresses as order_address_billing', function ($leftJoin) {
        $leftJoin->on('order_address_billing.order_id', '=', 'orders.id')
            ->where('order_address_billing.address_type', OrderAddress::ADDRESS_TYPE_BILLING);
    })
    ->leftJoin('order_payment', 'orders.id', '=', 'order_payment.order_id')
    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon-eye',
            'title'  => trans('shop::app.customers.account.orders.action-view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('shop.customers.account.orders.view', $row->id);
            },
        ]);
    }
}
