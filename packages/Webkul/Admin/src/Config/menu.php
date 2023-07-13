<?php

return [
    [
        'key'        => 'dashboard',
        'name'       => 'admin::app.layouts.dashboard',
        'route'      => 'admin.dashboard.index',
        'sort'       => 1,
        'icon'       => 'icon-dashboard',
        'icon-class' => 'dashboard-icon',
    ], [
        'key'        => 'sales',
        'name'       => 'admin::app.layouts.sales',
        'route'      => 'admin.sales.orders.index',
        'sort'       => 2,
        'icon'       => 'icon-sales',
        'icon-class' => 'sales-icon',
    ], [
        'key'        => 'sales.orders',
        'name'       => 'admin::app.layouts.orders',
        'route'      => 'admin.sales.orders.index',
        'sort'       => 1,
        'icon'       => '',
        'icon-class' => '',
    ], [
        'key'        => 'sales.shipments',
        'name'       => 'admin::app.layouts.shipments',
        'route'      => 'admin.sales.shipments.index',
        'sort'       => 2,
        'icon'       => '',
        'icon-class' => '',
    ], [
        'key'        => 'sales.invoices',
        'name'       => 'admin::app.layouts.invoices',
        'route'      => 'admin.sales.invoices.index',
        'sort'       => 3,
        'icon'       => '',
        'icon-class' => '',
    ], [
        'key'        => 'sales.refunds',
        'name'       => 'admin::app.layouts.refunds',
        'route'      => 'admin.sales.refunds.index',
        'sort'       => 4,
        'icon'       => '',
    ], [
        'key'        => 'sales.transactions',
        'name'       => 'admin::app.layouts.transactions',
        'route'      => 'admin.sales.transactions.index',
        'sort'       => 5,
        'icon'       => '',
    ], [
        'key'        => 'catalog',
        'name'       => 'admin::app.layouts.catalog',
        'route'      => 'admin.catalog.products.index',
        'sort'       => 3,
        'icon'       => 'icon-product',
        'icon-class' => 'catalog-icon',
    ], [
        'key'        => 'catalog.products',
        'name'       => 'admin::app.layouts.products',
        'route'      => 'admin.catalog.products.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'catalog.categories',
        'name'       => 'admin::app.layouts.categories',
        'route'      => 'admin.catalog.categories.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'catalog.attributes',
        'name'       => 'admin::app.layouts.attributes',
        'route'      => 'admin.catalog.attributes.index',
        'sort'       => 3,
        'icon'       => '',
    ], [
        'key'        => 'catalog.families',
        'name'       => 'admin::app.layouts.attribute-families',
        'route'      => 'admin.catalog.families.index',
        'sort'       => 4,
        'icon'       => '',
    ], [
        'key'        => 'customers',
        'name'       => 'admin::app.layouts.customers',
        'route'      => 'admin.customer.index',
        'sort'       => 4,
        'icon'       => 'icon-customer-2',
        'icon-class' => 'customer-icon',
    ], [
        'key'        => 'customers.customers',
        'name'       => 'admin::app.layouts.customers',
        'route'      => 'admin.customer.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'customers.groups',
        'name'       => 'admin::app.layouts.groups',
        'route'      => 'admin.groups.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'customers.reviews',
        'name'       => 'admin::app.layouts.reviews',
        'route'      => 'admin.customer.review.index',
        'sort'       => 3,
        'icon'       => '',
    ], [
        'key'        => 'configuration',
        'name'       => 'admin::app.layouts.configure',
        'route'      => 'admin.configuration.index',
        'sort'       => 7,
        'icon'       => 'icon-configuration',
        'icon-class' => 'configuration-icon',
    ], [
        'key'        => 'settings',
        'name'       => 'admin::app.layouts.settings',
        'route'      => 'admin.locales.index',
        'sort'       => 6,
        'icon'       => 'icon-settings',
        'icon-class' => 'settings-icon',
    ], [
        'key'        => 'settings.locales',
        'name'       => 'admin::app.layouts.locales',
        'route'      => 'admin.locales.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'settings.currencies',
        'name'       => 'admin::app.layouts.currencies',
        'route'      => 'admin.currencies.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'settings.exchange_rates',
        'name'       => 'admin::app.layouts.exchange-rates',
        'route'      => 'admin.exchange_rates.index',
        'sort'       => 3,
        'icon'       => '',
    ], [
        'key'        => 'settings.inventory_sources',
        'name'       => 'admin::app.layouts.inventory-sources',
        'route'      => 'admin.inventory_sources.index',
        'sort'       => 4,
        'icon'       => '',
    ], [
        'key'        => 'settings.channels',
        'name'       => 'admin::app.layouts.channels',
        'route'      => 'admin.channels.index',
        'sort'       => 5,
        'icon'       => '',
    ], [
        'key'        => 'settings.users',
        'name'       => 'admin::app.layouts.users',
        'route'      => 'admin.users.index',
        'sort'       => 6,
        'icon'       => '',
    ], [
        'key'        => 'settings.users.users',
        'name'       => 'admin::app.layouts.users',
        'route'      => 'admin.users.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'settings.users.roles',
        'name'       => 'admin::app.layouts.roles',
        'route'      => 'admin.roles.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'settings.sliders',
        'name'       => 'admin::app.layouts.sliders',
        'route'      => 'admin.sliders.index',
        'sort'       => 7,
        'icon'       => '',
    ], [
        'key'        => 'settings.taxes',
        'name'       => 'admin::app.layouts.taxes',
        'route'      => 'admin.tax_categories.index',
        'sort'       => 7,
        'icon'       => '',
    ], [
        'key'        => 'settings.taxes.tax-categories',
        'name'       => 'admin::app.layouts.tax-categories',
        'route'      => 'admin.tax_categories.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'settings.taxes.tax-rates',
        'name'       => 'admin::app.layouts.tax-rates',
        'route'      => 'admin.tax_rates.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'marketing',
        'name'       => 'admin::app.layouts.marketing',
        'route'      => 'admin.catalog_rules.index',
        'sort'       => 5,
        'icon'       => 'icon-promotion',
        'icon-class' => 'promotion-icon',
    ], [
        'key'        => 'marketing.promotions',
        'name'       => 'admin::app.layouts.promotions',
        'route'      => 'admin.catalog_rules.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'marketing.promotions.catalog-rules',
        'name'       => 'admin::app.promotions.catalog-rules.title',
        'route'      => 'admin.catalog_rules.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'marketing.promotions.cart-rules',
        'name'       => 'admin::app.promotions.cart-rules.title',
        'route'      => 'admin.cart_rules.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'marketing.email-marketing',
        'name'       => 'admin::app.layouts.email-marketing',
        'route'      => 'admin.email_templates.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'marketing.email-marketing.email-templates',
        'name'       => 'admin::app.layouts.email-templates',
        'route'      => 'admin.email_templates.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'marketing.email-marketing.events',
        'name'       => 'admin::app.layouts.events',
        'route'      => 'admin.events.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'marketing.email-marketing.campaigns',
        'name'       => 'admin::app.layouts.campaigns',
        'route'      => 'admin.campaigns.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'marketing.email-marketing.subscribers',
        'name'       => 'admin::app.layouts.newsletter-subscriptions',
        'route'      => 'admin.customers.subscribers.index',
        'sort'       => 3,
        'icon'       => '',
    ], [
        'key'        => 'marketing.sitemaps',
        'name'       => 'admin::app.layouts.sitemaps',
        'route'      => 'admin.sitemaps.index',
        'sort'       => 3,
        'icon'       => '',
    ], [
        'key'        => 'cms',
        'name'       => 'admin::app.layouts.cms',
        'route'      => 'admin.cms.index',
        'sort'       => 5,
        'icon'       => 'icon-cms',
        'icon-class' => 'cms-icon',
    ], [
        'key'        => 'cms.pages',
        'name'       => 'admin::app.cms.pages.pages',
        'route'      => 'admin.cms.index',
        'sort'       => 1,
        'icon'       => '',
    ]
];