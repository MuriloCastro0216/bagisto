{!! view_render_event('bagisto.admin.sales.order.create.left_component.shipping.before') !!}

<v-cart-shipping-methods
    :methods="shippingMethods"
    @processing="stepForward"
    @processed="stepProcessed"
>
    <!-- Shipping Method Shimmer Effect -->
</v-cart-shipping-methods>

{!! view_render_event('bagisto.admin.sales.order.create.left_component.shipping.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-cart-shipping-methods-template"
    >
        <div class="bg-white dark:bg-gray-900 rounded box-shadow">
            <div class="flex items-center p-4 border-b dark:border-gray-800">
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    @lang('admin::app.sales.orders.create.shipping.title')
                </p>
            </div>

            <!-- Shipping Cards -->
            <template v-if="! methods">
                <!-- Shipping Method Shimmer Effect -->
            </template>

            <template v-else>
                <div class="grid">
                    <template v-for="method in methods">
                        {!! view_render_event('bagisto.admin.sales.order.create.left_component.shipping.before') !!}

                        <label
                            class="grid gap-4 p-4 border-b dark:border-gray-800 cursor-pointer transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                            v-for="rate in method.rates"
                            :for="rate.method"
                        >
                            <div class="flex gap-4 justify-between">
                                <div class="flex gap-2 items-center">
                                    <x-admin::form.control-group.control
                                        type="radio"
                                        name="shipping_method"
                                        ::id="rate.method"
                                        ::for="rate.method"
                                        ::value="rate.method"
                                        @change="store(rate.method)"
                                    />

                                    <p class="text-base text-gray-600 font-medium">
                                        @{{ rate.method_title }}
                                    </p>
                                </div>

                                <p class="text-base text-blue-600">
                                    @{{ rate.base_formatted_price }}
                                </p>
                            </div>

                            <p class="text-base text-gray-600">
                                @{{ rate.method_description }}
                            </p>
                        </label>

                        {!! view_render_event('bagisto.admin.sales.order.create.left_component.shipping.after') !!}
                    </template>
                </div>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-cart-shipping-methods', {
            template: '#v-cart-shipping-methods-template',

            props: {
                methods: {
                    type: Object,
                    required: true,
                    default: () => null,
                },
            },

            emits: ['processing', 'processed'],

            methods: {
                store(selectedMethod) {
                    this.$emit('processing', 'payment');

                    this.$axios.post("{{ route('admin.sales.cart.shipping_methods.store', $cart->id) }}", {    
                            shipping_method: selectedMethod,
                        })
                        .then(response => {
                            if (response.data.redirect_url) {
                                window.location.href = response.data.redirect_url;
                            } else {
                                this.$emit('processed', response.data.payment_methods);
                            }
                        })
                        .catch(error => {
                            this.$emit('processing', 'shipping');

                            if (error.response.data.redirect_url) {
                                window.location.href = error.response.data.redirect_url;
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
