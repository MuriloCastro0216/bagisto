<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductDownloadableLink;

$factory->define(ProductDownloadableLink::class, function (Faker $faker) {
    $now = date("Y-m-d H:i:s");
    $filename = 'ProductImageExampleForUpload.jpg';
    $filepath = '/tests/_data/';
    return [
        'url' => '',
        'file' => $filepath . $filename,
        'file_name' => $filename,
        'type' => 'file',
        'price' => 0.0000,
        'downloads' => $faker->randomNumber(1),
        'product_id' => function () {
            return factory(Product::class)->create()->id;
        },
    ];
});
