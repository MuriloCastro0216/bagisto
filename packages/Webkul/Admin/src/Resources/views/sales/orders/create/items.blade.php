{!! view_render_event('bagisto.admin.sales.order.create.left_component.items.before') !!}

<!-- Vue JS Component -->
<v-cart-items
    :cart="cart"
></v-cart-items>

{!! view_render_event('bagisto.admin.sales.order.create.left_component.items.after') !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-cart-items-template">
        <div class="bg-white dark:bg-gray-900 rounded box-shadow">
            <div class="flex justify-between items-center p-4">
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    @lang('admin::app.sales.orders.create.items.title')
                </p>

                <div class="flex gap-4 items-center">
                    <p class="text-base text-gray-800 dark:text-white font-semibold">
                        @{{ "@lang('admin::app.sales.orders.create.items.sub-total', ['sub_total' => 'replace'])".replace('replace', cart.formatted_sub_total) }}
                    </p>

                    <button
                        class="secondary-button"
                        @click="$refs.productSearch.openDrawer()"
                    >
                        @lang('admin::app.sales.orders.create.items.add-product')
                    </button>
                </div>
            </div>

            <!-- Order items -->
            <div class="grid">
                <div
                    class="row grid p-4 bg-white dark:bg-gray-900 border-b dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                    v-for="item in cart.items"
                >
                    <!-- Item Information -->
                    <div class="flex justify-between gap-2.5">
                        <div class="flex gap-2.5">
                            <!-- Image -->
                            <div
                                class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded overflow-hidden"
                                :class="{'border border-dashed border-gray-300 dark:border-gray-800 rounded dark:invert dark:mix-blend-exclusion overflow-hidden': ! item.images.length}"
                            >
                                <template v-if="! item.images.length">
                                    <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                
                                    <p class="w-full absolute bottom-1.5 text-[6px] text-gray-400 text-center font-semibold">
                                        @lang('admin::app.catalog.products.edit.types.grouped.image-placeholder')
                                    </p>
                                </template>

                                <template v-else>
                                    <img :src="item.images[0].url">
                                </template>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <!-- Item Name -->
                                <p class="text-base text-gray-800 dark:text-white font-semibold">
                                    @{{ item.name }}
                                </p>

                                <!-- Item SKU -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ "@lang('admin::app.sales.orders.create.items.sku', ['sku' => ':replace'])".replace(':replace', item.sku) }}
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ "@lang('admin::app.sales.orders.create.items.amount-per-unit', ['amount' => ':replaceAmount', 'qty' => ':replaceQty'])".replace(':replaceAmount', item.formatted_price).replace(':replaceQty', item.quantity) }}
                                </p>

                                <!-- Item Options -->
                                <p class="text-gray-600 dark:text-gray-300 [&>*]:after:content-['_,_']">
                                    <span
                                        class="after:content-[','] last:after:content-['']"
                                        v-for="option in item.options"
                                    >
                                        @{{ option.attribute_name + ' : ' + option.option_label }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="grid">
                            <p class="text-base text-gray-800 dark:text-white font-semibold">
                                @{{ item.formatted_total }}
                            </p>
                        </div>
                    </div>

                    <!-- Item Actions -->
                    <div class="flex justify-end gap-2.5">
                        <p
                            class="text-red-600 cursor-pointer transition-all hover:underline"
                            @click="removeCartItem(item)"
                        >
                            @lang('admin::app.sales.orders.create.items.delete')
                        </p>

                        <p class="text-blue-600 cursor-pointer transition-all hover:underline">
                            @lang('admin::app.sales.orders.create.items.move-to-wishlist')
                        </p>
                    </div>
                </div>
            </div>

            <!-- Product Search Blade Component -->
            <x-admin::products.search
                ref="productSearch"
                ::added-product-ids="addedProductIds"
                @onProductAdded="addSelectedProducts($event)"
            />
        </div>
    </script>

    <script type="module">
        app.component('v-cart-items', {
            template: '#v-cart-items-template',

            props: ['cart'],

            data() {
                return {
                };
            },

            computed: {
                addedProductIds() {
                    let productIds = this.cart.items.map(item => item.product_id);

                    return productIds;
                }
            },

            methods: {
                addSelectedProducts(products) {
                    let params = {};

                    products.forEach(product => {
                        params[product.id] = {
                            product_id: product.id,
                            quantity: 1,
                        };
                    });

                    axios.post("{{ route('admin.sales.cart.store', $cart->id) }}", params)
                        .then(response => {
                            this.$emit('processing', 'review');
                        })
                        .catch(error => {
                            // Handle the error here
                        });
                },

                removeCartItem(item) {
                    axios.delete("{{ route('admin.sales.cart.destroy', $cart->id) }}", {
                        data: {
                            cart_item_id: item.id
                        }
                    })
                        .then(response => {
                            this.cart = response.data.data;
                        })
                        .catch(error => {
                            // Handle the error here
                        });
                }
            }
        });
    </script>
@endPushOnce