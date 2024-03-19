<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should display the cart items for a guest user', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $cart = Cart::factory()->create();

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->setCart($cart);

    cart()->putCart($cart);

    // Act and Assert
    $response = get(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', $cart->items_count)
        ->assertJsonPath('data.items_qty', $cart->items_qty)
        ->assertJsonPath('data.base_sub_total', core()->formatPrice($cart->base_sub_total))
        ->assertJsonPath('data.base_tax_total', ! empty($cart->base_tax_total) ? $cart->base_tax_total : 0)
        ->assertJsonPath('data.base_tax_amounts.0', core()->currency($cart->base_tax_amounts))
        ->assertJsonPath('data.formatted_base_discount_amount', core()->currency($cart->base_discount_amount))
        ->assertJsonPath('data.base_discount_amount', ! empty($cart->base_discount_amount) ? $cart->base_discount_amount : 0)
        ->assertJsonPath('data.grand_total', $cart->grand_total);

    foreach ($cart->items as $key => $cartItem) {
        $response->assertJsonPath('data.items.'.$key.'.id', $cartItem->id);
        $response->assertJsonPath('data.items.'.$key.'.quantity', $cartItem->quantity);
        $response->assertJsonPath('data.items.'.$key.'.type', $cartItem->type);
        $response->assertJsonPath('data.items.'.$key.'.name', $cartItem->name);
        $response->assertJsonPath('data.items.'.$key.'.price', $cartItem->price);
        $response->assertJsonPath('data.items.'.$key.'.formatted_price', core()->formatPrice($cartItem->price));
        $response->assertJsonPath('data.items.'.$key.'.total', $cartItem->total);
        $response->assertJsonPath('data.items.'.$key.'.formatted_total', core()->formatPrice($cartItem->total));
        $response->assertJsonPath('data.items.'.$key.'.options', $cartItem->options ?? []);
        $response->assertJsonPath('data.items.'.$key.'.product_url_key', $cartItem->product->url_key);
    }
});

it('should display the cart items for a customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->setCart($cart);

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = get(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', $cart->items_count)
        ->assertJsonPath('data.items_qty', $cart->items_qty)
        ->assertJsonPath('data.base_sub_total', core()->formatPrice($cart->base_sub_total))
        ->assertJsonPath('data.base_tax_total', ! empty($cart->base_tax_total) ? $cart->base_tax_total : 0)
        ->assertJsonPath('data.base_tax_amounts.0', core()->currency($cart->base_tax_amounts))
        ->assertJsonPath('data.formatted_base_discount_amount', core()->currency($cart->base_discount_amount))
        ->assertJsonPath('data.base_discount_amount', ! empty($cart->base_discount_amount) ? $cart->base_discount_amount : 0)
        ->assertJsonPath('data.grand_total', $cart->grand_total);

    foreach ($cart->items as $key => $cartItem) {
        $response->assertJsonPath('data.items.'.$key.'.id', $cartItem->id);
        $response->assertJsonPath('data.items.'.$key.'.quantity', $cartItem->quantity);
        $response->assertJsonPath('data.items.'.$key.'.type', $cartItem->type);
        $response->assertJsonPath('data.items.'.$key.'.name', $cartItem->name);
        $response->assertJsonPath('data.items.'.$key.'.price', $cartItem->price);
        $response->assertJsonPath('data.items.'.$key.'.formatted_price', core()->formatPrice($cartItem->price));
        $response->assertJsonPath('data.items.'.$key.'.total', $cartItem->total);
        $response->assertJsonPath('data.items.'.$key.'.formatted_total', core()->formatPrice($cartItem->total));
        $response->assertJsonPath('data.items.'.$key.'.options', $cartItem->options ?? []);
        $response->assertJsonPath('data.items.'.$key.'.product_url_key', $cartItem->product->url_key);
    }
});

it('should fails the validation error when the cart item id not provided when remove product items into the cart for a guest user', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $cart = Cart::factory()->create();

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->putCart($cart);

    // Act and Assert
    deleteJson(route('shop.api.checkout.cart.destroy'))
        ->assertJsonValidationErrorFor('cart_item_id')
        ->assertUnprocessable();
});

it('should fails the validation error when the cart item id not provided when remove product items into the cart for a customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->setCart($cart);

    // Act and Assert
    $this->loginAsCustomer($customer);

    deleteJson(route('shop.api.checkout.cart.destroy'))
        ->assertJsonValidationErrorFor('cart_item_id')
        ->assertUnprocessable();
});

it('should fails the validation error when the wrong cart item id provided when remove product items to the cart for a guest user', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $cart = Cart::factory()->create();

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->putCart($cart);

    // Act and Assert
    deleteJson(route('shop.api.checkout.cart.destroy'), [
        'cart_item_id' => 'WRONG_ID',
    ])
        ->assertJsonValidationErrorFor('cart_item_id')
        ->assertUnprocessable();
});

it('should fails the validation error when the wrong cart item id provided when remove product items to the cart for a customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->putCart($cart);

    $this->loginAsCustomer($customer);

    // Act and Assert
    deleteJson(route('shop.api.checkout.cart.destroy'), [
        'cart_item_id' => 'WRONG_ID',
    ])
        ->assertJsonValidationErrorFor('cart_item_id')
        ->assertUnprocessable();
});

it('should remove only one product item from the cart for the guest user', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $cart = Cart::factory()->create();

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    cart()->putCart($cart);

    // Act and Assert
    deleteJson(route('shop.api.checkout.cart.destroy', [
        'cart_item_id' => $cartItem->id,
    ]))
        ->assertOk()
        ->assertJsonPath('data', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.success-remove'));

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem->id,
    ]);

    $this->assertDatabaseMissing('cart', [
        'id' => $cart->id,
    ]);
});

it('should remove only one product item from the cart for the customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    // Act and Assert
    $this->loginAsCustomer($customer);

    deleteJson(route('shop.api.checkout.cart.destroy', [
        'cart_item_id' => $cartItem->id,
    ]))
        ->assertOk()
        ->assertJsonPath('data', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.success-remove'));

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem->id,
    ]);

    $this->assertDatabaseMissing('cart', [
        'id' => $cart->id,
    ]);
});

it('should only remove one product from the cart for now the cart will contains two products for a guest user', function () {
    // Arrange
    $products = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->count(2)
        ->create();

    [$product1, $product2] = $products;

    $cart = Cart::factory()->create();

    $additional1 = [
        'product_id' => $product1->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $additional2 = [
        'product_id' => $product2->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem1 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product1->id,
        'sku'               => $product1->sku,
        'quantity'          => $additional1['quantity'],
        'name'              => $product1->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product1->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional1['quantity'],
        'base_total'        => $price * $additional1['quantity'],
        'weight'            => $product1->weight ?? 0,
        'total_weight'      => ($product1->weight ?? 0) * $additional1['quantity'],
        'base_total_weight' => ($product1->weight ?? 0) * $additional1['quantity'],
        'type'              => $product1->type,
        'additional'        => $additional1,
    ]);

    $cartItem2 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product2->id,
        'sku'               => $product2->sku,
        'quantity'          => $additional2['quantity'],
        'name'              => $product2->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product2->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional2['quantity'],
        'base_total'        => $price * $additional2['quantity'],
        'weight'            => $product2->weight ?? 0,
        'total_weight'      => ($product2->weight ?? 0) * $additional2['quantity'],
        'base_total_weight' => ($product2->weight ?? 0) * $additional2['quantity'],
        'type'              => $product2->type,
        'additional'        => $additional2,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    cart()->putCart($cart);

    // Act and Assert
    $response = deleteJson(route('shop.api.checkout.cart.destroy'), [
        'cart_item_id' => $cartItem1->id,
    ])
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', $cart->items_count)
        ->assertJsonPath('data.items_qty', $cart->items_qty)
        ->assertJsonPath('data.base_sub_total', core()->formatPrice($cart->base_sub_total))
        ->assertJsonPath('data.base_tax_total', ! empty($cart->base_tax_total) ? $cart->base_tax_total : 0)
        ->assertJsonPath('data.base_tax_amounts.0', core()->currency($cart->base_tax_amounts))
        ->assertJsonPath('data.formatted_base_discount_amount', core()->currency($cart->base_discount_amount))
        ->assertJsonPath('data.base_discount_amount', ! empty($cart->base_discount_amount) ? $cart->base_discount_amount : 0)
        ->assertJsonPath('data.grand_total', $cart->grand_total)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.success-remove'));

    foreach ($cart->items as $key => $cartItem) {
        $response->assertJsonPath('data.items.'.$key.'.id', $cartItem->id);
        $response->assertJsonPath('data.items.'.$key.'.quantity', $cartItem->quantity);
        $response->assertJsonPath('data.items.'.$key.'.type', $cartItem->type);
        $response->assertJsonPath('data.items.'.$key.'.name', $cartItem->name);
        $response->assertJsonPath('data.items.'.$key.'.price', $cartItem->price);
        $response->assertJsonPath('data.items.'.$key.'.formatted_price', core()->formatPrice($cartItem->price));
        $response->assertJsonPath('data.items.'.$key.'.total', $cartItem->total);
        $response->assertJsonPath('data.items.'.$key.'.formatted_total', core()->formatPrice($cartItem->total));
        $response->assertJsonPath('data.items.'.$key.'.options', $cartItem->options ?? []);
        $response->assertJsonPath('data.items.'.$key.'.product_url_key', $cartItem->product->url_key);
    }

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem1->id,
    ]);

    $this->assertModelWise([
        Cart::class => [
            [
                'id'                     => $cart->id,
                'customer_email'         => $cart->customer_email,
                'customer_first_name'    => $cart->customer_first_name,
                'customer_last_name'     => $cart->customer_last_name,
                'shipping_method'        => $cart->shipping_method,
                'coupon_code'            => $cart->coupon_code,
                'is_gift'                => $cart->is_gift,
                'items_count'            => $cart->items_count,
                'items_qty'              => $cart->items_qty,
                'exchange_rate'          => $cart->exchange_rate,
                'global_currency_code'   => $cart->global_currency_code,
                'base_currency_code'     => $cart->base_currency_code,
                'channel_currency_code'  => $cart->channel_currency_code,
                'cart_currency_code'     => $cart->cart_currency_code,
                'grand_total'            => $cart->grand_total,
                'base_grand_total'       => $cart->base_grand_total,
                'sub_total'              => $cart->sub_total,
                'base_sub_total'         => $cart->base_sub_total,
                'tax_total'              => $cart->tax_total,
                'base_tax_total'         => $cart->base_tax_total,
                'discount_amount'        => $cart->discount_amount,
                'base_discount_amount'   => $cart->base_discount_amount,
                'checkout_method'        => $cart->checkout_method,
                'is_guest'               => $cart->is_guest,
                'is_active'              => $cart->is_active,
                'applied_cart_rule_ids'  => $cart->applied_cart_rule_ids,
                'customer_id'            => $cart->customer_id,
                'channel_id'             => $cart->channel_id,
            ],
        ],

        CartItem::class => [
            [
                'id'                    => $cartItem2->id,
                'quantity'              => $cartItem2->quantity,
                'sku'                   => $cartItem2->sku,
                'type'                  => $cartItem2->type,
                'name'                  => $cartItem2->name,
                'coupon_code'           => $cartItem2->coupon_code,
                'weight'                => $cartItem2->weight,
                'total_weight'          => $cartItem2->total_weight,
                'base_total_weight'     => $cartItem2->base_total_weight,
                'price'                 => $cartItem2->price,
                'base_price'            => $cartItem2->base_price,
                'custom_price'          => $cartItem2->custom_price,
                'total'                 => $cartItem2->total,
                'base_total'            => $cartItem2->base_total,
                'tax_percent'           => number_format($cartItem2->tax_percent, 4),
                'tax_amount'            => number_format($cartItem2->tax_amount, 4),
                'base_tax_amount'       => number_format($cartItem2->base_tax_amount, 4),
                'discount_amount'       => number_format($cartItem2->discount_amount, 4),
                'base_discount_amount'  => number_format($cartItem2->base_discount_amount, 4),
                'parent_id'             => $cartItem2->parent_id,
                'cart_id'               => $cartItem2->cart_id,
                'tax_category_id'       => $cartItem2->tax_category_id,
                'applied_cart_rule_ids' => $cartItem2->applied_cart_rule_ids,
            ],
        ],
    ]);
});

it('should only remove one product from the cart for now the cart will contains two products for a customer', function () {
    // Arrange
    $products = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->count(2)
        ->create();

    [$product1, $product2] = $products;

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional1 = [
        'product_id' => $product1->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $additional2 = [
        'product_id' => $product2->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem1 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product1->id,
        'sku'               => $product1->sku,
        'quantity'          => $additional1['quantity'],
        'name'              => $product1->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product1->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional1['quantity'],
        'base_total'        => $price * $additional1['quantity'],
        'weight'            => $product1->weight ?? 0,
        'total_weight'      => ($product1->weight ?? 0) * $additional1['quantity'],
        'base_total_weight' => ($product1->weight ?? 0) * $additional1['quantity'],
        'type'              => $product1->type,
        'additional'        => $additional1,
    ]);

    $cartItem2 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product2->id,
        'sku'               => $product2->sku,
        'quantity'          => $additional2['quantity'],
        'name'              => $product2->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product2->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional2['quantity'],
        'base_total'        => $price * $additional2['quantity'],
        'weight'            => $product2->weight ?? 0,
        'total_weight'      => ($product2->weight ?? 0) * $additional2['quantity'],
        'base_total_weight' => ($product2->weight ?? 0) * $additional2['quantity'],
        'type'              => $product2->type,
        'additional'        => $additional2,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    // Act and Assert
    $this->loginAsCustomer();

    $response = deleteJson(route('shop.api.checkout.cart.destroy'), [
        'cart_item_id' => $cartItem1->id,
    ])
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', $cart->items_count)
        ->assertJsonPath('data.items_qty', $cart->items_qty)
        ->assertJsonPath('data.base_sub_total', core()->formatPrice($cart->base_sub_total))
        ->assertJsonPath('data.base_tax_total', ! empty($cart->base_tax_total) ? $cart->base_tax_total : 0)
        ->assertJsonPath('data.base_tax_amounts.0', core()->currency($cart->base_tax_amounts))
        ->assertJsonPath('data.formatted_base_discount_amount', core()->currency($cart->base_discount_amount))
        ->assertJsonPath('data.base_discount_amount', ! empty($cart->base_discount_amount) ? $cart->base_discount_amount : 0)
        ->assertJsonPath('data.grand_total', $cart->grand_total)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.success-remove'));

    foreach ($cart->items as $key => $cartItem) {
        $response->assertJsonPath('data.items.'.$key.'.id', $cartItem->id);
        $response->assertJsonPath('data.items.'.$key.'.quantity', $cartItem->quantity);
        $response->assertJsonPath('data.items.'.$key.'.type', $cartItem->type);
        $response->assertJsonPath('data.items.'.$key.'.name', $cartItem->name);
        $response->assertJsonPath('data.items.'.$key.'.price', $cartItem->price);
        $response->assertJsonPath('data.items.'.$key.'.formatted_price', core()->formatPrice($cartItem->price));
        $response->assertJsonPath('data.items.'.$key.'.total', $cartItem->total);
        $response->assertJsonPath('data.items.'.$key.'.formatted_total', core()->formatPrice($cartItem->total));
        $response->assertJsonPath('data.items.'.$key.'.options', $cartItem->options ?? []);
        $response->assertJsonPath('data.items.'.$key.'.product_url_key', $cartItem->product->url_key);
    }

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem1->id,
    ]);

    $this->assertModelWise([
        Cart::class => [
            [
                'id'                     => $cart->id,
                'customer_email'         => $cart->customer_email,
                'customer_first_name'    => $cart->customer_first_name,
                'customer_last_name'     => $cart->customer_last_name,
                'shipping_method'        => $cart->shipping_method,
                'coupon_code'            => $cart->coupon_code,
                'is_gift'                => $cart->is_gift,
                'items_count'            => $cart->items_count,
                'items_qty'              => $cart->items_qty,
                'exchange_rate'          => $cart->exchange_rate,
                'global_currency_code'   => $cart->global_currency_code,
                'base_currency_code'     => $cart->base_currency_code,
                'channel_currency_code'  => $cart->channel_currency_code,
                'cart_currency_code'     => $cart->cart_currency_code,
                'grand_total'            => $cart->grand_total,
                'base_grand_total'       => $cart->base_grand_total,
                'sub_total'              => $cart->sub_total,
                'base_sub_total'         => $cart->base_sub_total,
                'tax_total'              => $cart->tax_total,
                'base_tax_total'         => $cart->base_tax_total,
                'discount_amount'        => $cart->discount_amount,
                'base_discount_amount'   => $cart->base_discount_amount,
                'checkout_method'        => $cart->checkout_method,
                'is_guest'               => $cart->is_guest,
                'is_active'              => $cart->is_active,
                'applied_cart_rule_ids'  => $cart->applied_cart_rule_ids,
                'customer_id'            => $cart->customer_id,
                'channel_id'             => $cart->channel_id,
            ],
        ],

        CartItem::class => [
            [
                'id'                    => $cartItem2->id,
                'quantity'              => $cartItem2->quantity,
                'sku'                   => $cartItem2->sku,
                'type'                  => $cartItem2->type,
                'name'                  => $cartItem2->name,
                'coupon_code'           => $cartItem2->coupon_code,
                'weight'                => $cartItem2->weight,
                'total_weight'          => $cartItem2->total_weight,
                'base_total_weight'     => $cartItem2->base_total_weight,
                'price'                 => $cartItem2->price,
                'base_price'            => $cartItem2->base_price,
                'custom_price'          => $cartItem2->custom_price,
                'total'                 => $cartItem2->total,
                'base_total'            => $cartItem2->base_total,
                'tax_percent'           => number_format($cartItem2->tax_percent, 4),
                'tax_amount'            => number_format($cartItem2->tax_amount, 4),
                'base_tax_amount'       => number_format($cartItem2->base_tax_amount, 4),
                'discount_amount'       => number_format($cartItem2->discount_amount, 4),
                'base_discount_amount'  => number_format($cartItem2->base_discount_amount, 4),
                'parent_id'             => $cartItem2->parent_id,
                'cart_id'               => $cartItem2->cart_id,
                'tax_category_id'       => $cartItem2->tax_category_id,
                'applied_cart_rule_ids' => $cartItem2->applied_cart_rule_ids,
            ],
        ],
    ]);
});

it('should remove all products from the cart for a guest user', function () {
    // Arrange
    $products = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->count(2)
        ->create();

    [$product1, $product2] = $products;

    $cart = Cart::factory()->create();

    $additional1 = [
        'product_id' => $product1->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $additional2 = [
        'product_id' => $product2->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem1 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product1->id,
        'sku'               => $product1->sku,
        'quantity'          => $additional1['quantity'],
        'name'              => $product1->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product1->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional1['quantity'],
        'base_total'        => $price * $additional1['quantity'],
        'weight'            => $product1->weight ?? 0,
        'total_weight'      => ($product1->weight ?? 0) * $additional1['quantity'],
        'base_total_weight' => ($product1->weight ?? 0) * $additional1['quantity'],
        'type'              => $product1->type,
        'additional'        => $additional1,
    ]);

    $cartItem2 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product2->id,
        'sku'               => $product2->sku,
        'quantity'          => $additional2['quantity'],
        'name'              => $product2->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product2->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional2['quantity'],
        'base_total'        => $price * $additional2['quantity'],
        'weight'            => $product2->weight ?? 0,
        'total_weight'      => ($product2->weight ?? 0) * $additional2['quantity'],
        'base_total_weight' => ($product2->weight ?? 0) * $additional2['quantity'],
        'type'              => $product2->type,
        'additional'        => $additional2,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    // Act and Assert
    deleteJson(route('shop.api.checkout.cart.destroy_selected'), [
        'ids' => [$cartItem1->id, $cartItem2->id],
    ]);

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem1->id,
    ]);

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem2->id,
    ]);
});

it('should remove all products from the cart for a customer', function () {
    // Arrange
    $products = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->count(2)
        ->create();

    [$product1, $product2] = $products;

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional1 = [
        'product_id' => $product1->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $additional2 = [
        'product_id' => $product2->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem1 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product1->id,
        'sku'               => $product1->sku,
        'quantity'          => $additional1['quantity'],
        'name'              => $product1->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product1->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional1['quantity'],
        'base_total'        => $price * $additional1['quantity'],
        'weight'            => $product1->weight ?? 0,
        'total_weight'      => ($product1->weight ?? 0) * $additional1['quantity'],
        'base_total_weight' => ($product1->weight ?? 0) * $additional1['quantity'],
        'type'              => $product1->type,
        'additional'        => $additional1,
    ]);

    $cartItem2 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product2->id,
        'sku'               => $product2->sku,
        'quantity'          => $additional2['quantity'],
        'name'              => $product2->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product2->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional2['quantity'],
        'base_total'        => $price * $additional2['quantity'],
        'weight'            => $product2->weight ?? 0,
        'total_weight'      => ($product2->weight ?? 0) * $additional2['quantity'],
        'base_total_weight' => ($product2->weight ?? 0) * $additional2['quantity'],
        'type'              => $product2->type,
        'additional'        => $additional2,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    // Act and Assert
    $this->loginAsCustomer();

    deleteJson(route('shop.api.checkout.cart.destroy_selected'), [
        'ids' => [$cartItem1->id, $cartItem2->id],
    ]);

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem1->id,
    ]);

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem2->id,
    ]);
});

it('should update cart quantities for guest user', function () {
    // Arrange
    $products = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->count(2)
        ->create();

    [$product1, $product2] = $products;

    $cart = Cart::factory()->create();

    $additional1 = [
        'product_id' => $product1->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $additional2 = [
        'product_id' => $product2->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem1 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product1->id,
        'sku'               => $product1->sku,
        'quantity'          => $additional1['quantity'],
        'name'              => $product1->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product1->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional1['quantity'],
        'base_total'        => $price * $additional1['quantity'],
        'weight'            => $product1->weight ?? 0,
        'total_weight'      => ($product1->weight ?? 0) * $additional1['quantity'],
        'base_total_weight' => ($product1->weight ?? 0) * $additional1['quantity'],
        'type'              => $product1->type,
        'additional'        => $additional1,
    ]);

    $cartItem2 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product2->id,
        'sku'               => $product2->sku,
        'quantity'          => $additional2['quantity'],
        'name'              => $product2->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product2->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional2['quantity'],
        'base_total'        => $price * $additional2['quantity'],
        'weight'            => $product2->weight ?? 0,
        'total_weight'      => ($product2->weight ?? 0) * $additional2['quantity'],
        'base_total_weight' => ($product2->weight ?? 0) * $additional2['quantity'],
        'type'              => $product2->type,
        'additional'        => $additional2,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    cart()->putCart($cart);

    // Act and Assert
    $response = putJson(route('shop.api.checkout.cart.update'), [
        'qty' => [
            $cartItem1->id => rand(2, 10),
            $cartItem2->id => rand(2, 10),
        ],
    ])
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', $cart->items_count)
        ->assertJsonPath('data.items_qty', $cart->items_qty)
        ->assertJsonPath('data.base_sub_total', core()->formatPrice($cart->base_sub_total))
        ->assertJsonPath('data.base_tax_total', ! empty($cart->base_tax_total) ? $cart->base_tax_total : 0)
        ->assertJsonPath('data.base_tax_amounts.0', core()->currency($cart->base_tax_amounts))
        ->assertJsonPath('data.formatted_base_discount_amount', core()->currency($cart->base_discount_amount))
        ->assertJsonPath('data.grand_total', $cart->grand_total)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.index.quantity-update'));

    foreach ($cart->items as $key => $cartItem) {
        $response->assertJsonPath('data.items.'.$key.'.id', $cartItem->id);
        $response->assertJsonPath('data.items.'.$key.'.quantity', $cartItem->quantity);
        $response->assertJsonPath('data.items.'.$key.'.type', $cartItem->type);
        $response->assertJsonPath('data.items.'.$key.'.name', $cartItem->name);
        $response->assertJsonPath('data.items.'.$key.'.price', $cartItem->price);
        $response->assertJsonPath('data.items.'.$key.'.formatted_price', core()->formatPrice($cartItem->price));
        $response->assertJsonPath('data.items.'.$key.'.total', $cartItem->total);
        $response->assertJsonPath('data.items.'.$key.'.formatted_total', core()->formatPrice($cartItem->total));
        $response->assertJsonPath('data.items.'.$key.'.options', $cartItem->options ?? []);
        $response->assertJsonPath('data.items.'.$key.'.product_url_key', $cartItem->product->url_key);
    }
});

it('should update cart quantities for customer', function () {
    // Arrange
    $products = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->count(2)
        ->create();

    [$product1, $product2] = $products;

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional1 = [
        'product_id' => $product1->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $additional2 = [
        'product_id' => $product2->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem1 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product1->id,
        'sku'               => $product1->sku,
        'quantity'          => $additional1['quantity'],
        'name'              => $product1->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product1->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional1['quantity'],
        'base_total'        => $price * $additional1['quantity'],
        'weight'            => $product1->weight ?? 0,
        'total_weight'      => ($product1->weight ?? 0) * $additional1['quantity'],
        'base_total_weight' => ($product1->weight ?? 0) * $additional1['quantity'],
        'type'              => $product1->type,
        'additional'        => $additional1,
    ]);

    $cartItem2 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product2->id,
        'sku'               => $product2->sku,
        'quantity'          => $additional2['quantity'],
        'name'              => $product2->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product2->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional2['quantity'],
        'base_total'        => $price * $additional2['quantity'],
        'weight'            => $product2->weight ?? 0,
        'total_weight'      => ($product2->weight ?? 0) * $additional2['quantity'],
        'base_total_weight' => ($product2->weight ?? 0) * $additional2['quantity'],
        'type'              => $product2->type,
        'additional'        => $additional2,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    // Act and Assert
    $this->loginAsCustomer();

    $response = putJson(route('shop.api.checkout.cart.update'), [
        'qty' => [
            $cartItem1->id => rand(2, 10),
            $cartItem2->id => rand(2, 10),
        ],
    ])
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', $cart->items_count)
        ->assertJsonPath('data.items_qty', $cart->items_qty)
        ->assertJsonPath('data.base_sub_total', core()->formatPrice($cart->base_sub_total))
        ->assertJsonPath('data.base_tax_total', ! empty($cart->base_tax_total) ? $cart->base_tax_total : 0)
        ->assertJsonPath('data.base_tax_amounts.0', core()->currency($cart->base_tax_amounts))
        ->assertJsonPath('data.formatted_base_discount_amount', core()->currency($cart->base_discount_amount))
        ->assertJsonPath('data.grand_total', $cart->grand_total)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.index.quantity-update'));

    foreach ($cart->items as $key => $cartItem) {
        $response->assertJsonPath('data.items.'.$key.'.id', $cartItem->id);
        $response->assertJsonPath('data.items.'.$key.'.quantity', $cartItem->quantity);
        $response->assertJsonPath('data.items.'.$key.'.type', $cartItem->type);
        $response->assertJsonPath('data.items.'.$key.'.name', $cartItem->name);
        $response->assertJsonPath('data.items.'.$key.'.price', $cartItem->price);
        $response->assertJsonPath('data.items.'.$key.'.formatted_price', core()->formatPrice($cartItem->price));
        $response->assertJsonPath('data.items.'.$key.'.total', $cartItem->total);
        $response->assertJsonPath('data.items.'.$key.'.formatted_total', core()->formatPrice($cartItem->total));
        $response->assertJsonPath('data.items.'.$key.'.options', $cartItem->options ?? []);
        $response->assertJsonPath('data.items.'.$key.'.product_url_key', $cartItem->product->url_key);
    }
});

it('should fails the validation error when the product id not provided when add a simple product to the cart', function () {
    // Arrange
    (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store', [
        'quantity' => rand(1, 10),
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a simple product to the cart for guest user', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $quantity = rand(1, 10),
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.is_guest', 1)
        ->assertJsonPath('data.customer_id', null)
        ->assertJsonPath('data.items_qty', $quantity)
        ->assertJsonPath('data.base_tax_total', 0)
        ->assertJsonPath('data.base_discount_amount', 0)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.items.0.quantity', $quantity)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'));

    $this->assertEquality($product->price, $response['data']['items'][0]['price']);

    $this->assertEquality($product->price * $quantity, $response['data']['grand_total']);

    $this->assertEquality($product->price * $quantity, $response['data']['sub_total']);
});

it('should add a simple product to the cart for customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    // Act and Assert
    $customer = $this->loginAsCustomer();

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $quantity = rand(1, 10),
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.is_guest', 0)
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.items_qty', $quantity)
        ->assertJsonPath('data.base_tax_total', 0)
        ->assertJsonPath('data.base_discount_amount', 0)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.items.0.quantity', $quantity)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'));

    $this->assertEquality($product->price, $response['data']['items'][0]['price']);

    $this->assertEquality($product->price * $quantity, $response['data']['grand_total']);

    $this->assertEquality($product->price * $quantity, $response['data']['sub_total']);
});

it('should fails the validation error when the product id not provided add a bundle product to the cart', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a bundle product to the cart for guest user', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.is_guest', 1)
        ->assertJsonPath('data.customer_id', null)
        ->assertJsonPath('data.base_tax_total', 0)
        ->assertJsonPath('data.base_discount_amount', 0)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'));

    $this->assertEquality($grandTotal, $response['data']['grand_total']);

    $this->assertEquality($grandTotal, $response['data']['sub_total']);
});

it('should add a bundle product to the cart for customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $customer = $this->loginAsCustomer();

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.is_guest', 0)
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.base_tax_total', 0)
        ->assertJsonPath('data.base_discount_amount', 0)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'));

    $this->assertEquality($grandTotal, $response['data']['grand_total']);

    $this->assertEquality($grandTotal, $response['data']['sub_total']);
});

it('should fails the validation when the product id not provided when add a configurable product to the cart', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a configurable product to the cart for guest user', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.is_guest', 1)
        ->assertJsonPath('data.base_discount_amount', 0)
        ->assertJsonPath('data.base_tax_total', 0)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.customer_id', null)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'));

    $this->assertEquality($childProduct->price, $response['data']['grand_total']);

    $this->assertEquality($childProduct->price, $response['data']['sub_total']);
});

it('should add a configurable product to the cart for customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $customer = $this->loginAsCustomer();

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.is_guest', 0)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.base_tax_total', 0)
        ->assertJsonPath('data.base_discount_amount', 0);

    $this->assertEquality($childProduct->price, $response['data']['grand_total']);

    $this->assertEquality($childProduct->price, $response['data']['sub_total']);
});

it('should fails the validation error when the product id not provided when add a downloadable product to the cart', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getDownloadableProductFactory()->create();

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store', [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'links'      => $product->downloadable_links()->pluck('id')->toArray(),
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a downloadable product to the cart for guest user', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getDownloadableProductFactory()->create();

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'links'      => $product->downloadable_links()->pluck('id')->toArray(),
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.is_guest', 1)
        ->assertJsonPath('data.have_stockable_items', false)
        ->assertJsonPath('data.customer_id', null)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.base_tax_total', 0)
        ->assertJsonPath('data.base_discount_amount', 0);

    $this->assertEquality($product->price, $response['data']['items'][0]['price']);

    $this->assertEquality($product->price, $response['data']['grand_total']);
});

it('should add a downloadable product to the cart for customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getDownloadableProductFactory()->create();

    // Act and Assert
    $customer = $this->loginAsCustomer();

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'links'      => $product->downloadable_links()->pluck('id')->toArray(),
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.is_guest', 0)
        ->assertJsonPath('data.have_stockable_items', false)
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.base_tax_total', 0)
        ->assertJsonPath('data.base_discount_amount', 0);

    $this->assertEquality($product->price, $response['data']['items'][0]['price']);

    $this->assertEquality($product->price, $response['data']['grand_total']);
});

it('should fails the validation error when the product id not provided when add a grouped product to the cart', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a grouped product to the cart for guest user', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.is_guest', 1)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.customer_id', null)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.base_tax_total', 0)
        ->assertJsonPath('data.base_discount_amount', 0);

    foreach ($groupedProducts as $key => $groupedProduct) {
        $response->assertJsonPath('data.items.'.$key.'.quantity', $groupedProduct->qty)
            ->assertJsonPath('data.items.'.$key.'.type', $groupedProduct->associated_product->type)
            ->assertJsonPath('data.items.'.$key.'.name', $groupedProduct->associated_product->name);
    }

    $this->assertEquals(round(array_sum($data['prices']), 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round(array_sum($data['prices']), 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should add a grouped product to the cart for customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert
    $customer = $this->loginAsCustomer();

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.is_guest', 0)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.base_tax_total', 0)
        ->assertJsonPath('data.base_discount_amount', 0);

    foreach ($groupedProducts as $key => $groupedProduct) {
        $response->assertJsonPath('data.items.'.$key.'.quantity', $groupedProduct->qty)
            ->assertJsonPath('data.items.'.$key.'.type', $groupedProduct->associated_product->type)
            ->assertJsonPath('data.items.'.$key.'.name', $groupedProduct->associated_product->name);
    }

    $this->assertEquals(round(array_sum($data['prices']), 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round(array_sum($data['prices']), 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the product id not provided when add a virtual product to the cart', function () {
    // Arrange
    (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getVirtualProductFactory()->create();

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store', [
        'quantity' => rand(1, 10),
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a virtual product to the cart for guest user', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getVirtualProductFactory()->create();

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $quantity = rand(1, 10),
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $quantity)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', $quantity)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.is_guest', 1)
        ->assertJsonPath('data.have_stockable_items', false)
        ->assertJsonPath('data.customer_id', null)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.base_tax_total', 0)
        ->assertJsonPath('data.base_discount_amount', 0);

    $this->assertEquality($product->price, $response['data']['items'][0]['price']);

    $this->assertEquality($product->price * $quantity, $response['data']['grand_total']);
});

it('should add a virtual product to the cart for customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getVirtualProductFactory()->create();

    // Act and Assert
    $customer = $this->loginAsCustomer();

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $quantity = rand(1, 10),
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $quantity)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', $quantity)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.is_guest', 0)
        ->assertJsonPath('data.have_stockable_items', false)
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.base_tax_total', 0)
        ->assertJsonPath('data.base_discount_amount', 0);

    $this->assertEquality($product->price, $response['data']['items'][0]['price']);

    $this->assertEquality($product->price * $quantity, $response['data']['grand_total']);
});
