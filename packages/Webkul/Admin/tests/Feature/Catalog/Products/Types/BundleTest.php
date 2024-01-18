<?php

use Webkul\Attribute\Models\Attribute;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product as ProductModel;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

afterEach(function () {
    /**
     * Clean up all the records.
     */
    ProductModel::query()->delete();
    Attribute::query()->whereNotBetween('id', [1, 28])->delete();
});

it('should return the create page of bundle product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    $productId = $product->id + 1;

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type'                => 'bundle',
        'attribute_family_id' => 1,
        'sku'                 => $sku = fake()->slug(),
    ])
        ->assertOk()
        ->assertJsonPath('data.redirect_url', route('admin.catalog.products.edit', $productId));

    $this->assertDatabaseHas('products', [
        'id'   => $productId,
        'type' => 'bundle',
        'sku'  => $sku,
    ]);
});

it('should return the edit page of bundle product', function () {
    // Arrange
    $product = (new ProductFaker())->getBundleProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSeeText(trans('admin::app.account.edit.back-btn'))
        ->assertSeeText($product->url_key)
        ->assertSeeText($product->name)
        ->assertSeeText($product->short_description)
        ->assertSeeText($product->description);
});

it('should update the bundle product', function () {
    // Arrange
    $product = (new ProductFaker())->getBundleProductFactory()->create();

    $options = [];

    $bundleOptions = $product->bundle_options();

    foreach ($bundleOptions as $key => $option) {
        $products = [];

        foreach ($option->bundle_option_products as $key => $bundleOption) {
            $products[$option->id]['product_id'] = $bundleOption->product_id;
            $products[$option->id]['sort_order'] = $key;
            $products[$option->id]['qty'] = 1;
        }

        $options[$option->id] = [
            app()->getLocale() => [
                'label' => fake()->words(3, true),
            ],
            'type'        => fake()->randomElement(['select', 'radio', 'checkbox', 'multiselect']),
            'is_required' => '1',
            'sort_order'  => $key,
            'products'    => $products,
        ];
    }

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'sku'                  => $product->sku,
        'url_key'              => $product->url_key,
        'short_description'    => $shortDescription = fake()->sentence(),
        'description'          => $description = fake()->paragraph(),
        'name'                 => $name = fake()->words(3, true),
        'price'                => $price = fake()->randomFloat(2, 1, 1000),
        'weight'               => $weight = fake()->numberBetween(0, 100),
        'channel'              => $channel = core()->getCurrentChannelCode(),
        'locale'               => $locale = app()->getLocale(),
        'bundle_options'       => $options,
        'new'                  => '1',
        'featured'             => '1',
        'visible_individually' => '1',
        'status'               => '1',
        'guest_checkout'       => '1',
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    $this->assertDatabaseHas('products', [
        'id'                  => $product->id,
        'type'                => $product->type,
        'sku'                 => $product->sku,
        'attribute_family_id' => 1,
        'parent_id'           => null,
        'additional'          => null,
    ]);

    $this->assertDatabaseHas('product_flat', [
        'url_key'           => $product->url_key,
        'type'              => 'bundle',
        'name'              => $name,
        'short_description' => $shortDescription,
        'description'       => $description,
        'price'             => $price,
        'weight'            => $weight,
        'locale'            => $locale,
        'product_id'        => $product->id,
        'channel'           => $channel,
    ]);

    foreach ($bundleOptions as $product) {
        $product->refresh();

        $this->assertDatabaseHas('product_flat', [
            'url_key'           => $product->url_key,
            'type'              => 'simple',
            'name'              => $product->name,
            'short_description' => $product->short_description,
            'description'       => $product->description,
            'price'             => $product->price,
            'weight'            => $product->weight,
            'locale'            => $locale,
            'product_id'        => $product->id,
            'channel'           => $channel,
        ]);
    }
});

it('should delete a bundle product', function () {
    // Arrange
    $product = (new ProductFaker())->getBundleProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.products.delete', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);

    foreach ($product->bundle_options() as $option) {
        $this->assertDatabaseMissing('product_bundle_options', [
            'id' => $option->id,
        ]);

        $this->assertDatabaseMissing('product_bundle_option_products', [
            'id' => $option->id,
        ]);
    }
});
