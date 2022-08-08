<?php

namespace Webkul\Product\Type;

class Simple extends AbstractType
{
    /**
     * These blade files will be included in product edit page.
     *
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.inventories',
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.videos',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.channels',
        'admin::catalog.products.accordians.product-links',
    ];

    /**
     * Show quantity box.
     *
     * @var bool
     */
    protected $showQuantityBox = true;

    /**
     * Return true if this product type is saleable. Saleable check added because
     * this is the point where all parent product will recall this.
     *
     * @return bool
     */
    public function isSaleable()
    {
        return $this->checkInLoadedSaleableChecks($this->product, function ($product) {
            if (! $product->status) {
                return false;
            }

            if (
                is_callable(config('products.isSaleable')) &&
                call_user_func(config('products.isSaleable'), $product) === false
            ) {
                return false;
            }

            if ($this->haveSufficientQuantity(1)) {
                return true;
            }

            return false;
        });
    }

    /**
     * Have sufficient quantity.
     *
     * @param  int  $qty
     * @return bool
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        $backOrders = core()->getConfigData('catalog.inventory.stock_options.backorders');

        $backOrders = ! is_null($backOrders) ? $backOrders : false;

        return $qty <= $this->totalQuantity() ? true : $backOrders;
    }

    /**
     * Get product maximum price.
     *
     * @return float
     */
    public function getMaximumPrice()
    {
        return $this->product->price;
    }
}
