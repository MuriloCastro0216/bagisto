@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $avgRatings = round($reviewHelper->getAverageRating($product));

    $percentageRatings = $reviewHelper->getPercentageRating($product);

    $customAttributeValues = $productViewHelper->getAdditionalData($product);
@endphp

<x-shop::layouts>
    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <v-product :product-id="{{ $product->id }}">
        <x-shop::shimmer.products.view></x-shop::shimmer.products.view>
    </v-product>

    {{-- Information Section --}}
    <x-shop::tabs position="center">
        <x-shop::tabs.item
            class="container mt-[60px] !p-0"
            {{-- @translations --}}
            :title="trans('Description')"
            :is-selected="true"
        >
            <p class="text-[#7D7D7D] text-[18px] max-1180:text-[14px]">
                {!! $product->description !!}
            </p>
        </x-shop::tabs.item>

        <x-shop::tabs.item
            class="container mt-[60px] !p-0"
            {{-- @translations --}}
            :title="trans('Additional Information')"
            :is-selected="false"
        >
            <p class="text-[#7D7D7D] text-[18px] max-1180:text-[14px]">
                @foreach ($customAttributeValues as $values)
                    <div class="grid">
                        <p class="text-[16px] text-black">{{ $values['label'] }}</p>
                    </div>
                    <div class="grid">
                        <p class="text-[16px] text-[#7D7D7D]">{{ $values['value']??'-' }}</p>
                    </div>
                @endforeach
            </p>
        </x-shop::tabs.item>

        <x-shop::tabs.item
            class="container mt-[60px] !p-0"
            {{-- @translations --}}
            :title="trans('Reviews')"
            :is-selected="false"
        >
            @include('shop::products.view.reviews')
        </x-shop::tabs.item>
    </x-shop::tabs>

    {{-- Featured Products --}}
    <x-shop::products.carousel
        {{-- @translations --}}
        :title="trans('Related Products')"
        :src="route('shop.products.related.index', ['id' => $product->id])"
    >
    </x-shop::products.carousel>

    {{-- Upsell Products --}}
    <x-shop::products.carousel
        {{-- @translations --}}
        :title="trans('We found other products you might like!')"
        :src="route('shop.products.up-sell.index', ['id' => $product->id])"
    >
    </x-shop::products.carousel>

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-product-template">
            <form action="{{ route('shop.cart.add', $product->id) }}" method="POST">
                @csrf

                <input 
                    type="hidden" 
                    name="product_id" 
                    value="{{ $product->id }}"
                >
                
                <input 
                    type="hidden" 
                    name="quantity" 
                    :value="qty"
                >

                <div class="container px-[60px] max-1180:px-[0px]">
                    <div class="flex mt-[48px] gap-[40px] max-1180:flex-wrap max-lg:mt-0 max-sm:gap-y-[25px]">
                        @include('shop::products.view.gallery')

                        {{-- Product Details --}}
                        <div class="max-w-[590px] relative max-1180:px-[20px]">
                            <div class="flex justify-between gap-[15px]">
                                <h1 class="text-[30px] font-medium max-sm:text-[20px]">
                                    {{ $product->name }}
                                </h1>

                                <div
                                    class="flex border border-black items-center justify-center rounded-full min-w-[46px] min-h-[46px] max-h-[46px] bg-white cursor-pointer transition icon-heart text-[24px]"
                                    @click='addToWishlist()'
                                >
                                </div>
                            </div>

                            <div class='flex items-center'>
                                <x-shop::products.star-rating star='{{ $avgRatings }}' :is-editable=false></x-shop::products.star-rating>

                                <div class="flex gap-[15px] items-center">
                                    <p class="text-[#7D7D7D] text-[14px]">({{ count($product->reviews) }} reviews)</p>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

                            <p class="text-[24px] flex items-center font-medium mt-[25px] max-sm:mt-[15px] max-sm:text-[18px]">
                                {!! $product->getTypeInstance()->getPriceHtml() !!}
                            </p>

                            {!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}

                            {!! view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]) !!}

                            <p class="text-[18px] text-[#7D7D7D] mt-[25px] max-sm:text-[14px] max-sm:mt-[15px]">
                                {!! $product->short_description !!}
                            </p>

                            {!! view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]) !!}

                            @include('shop::products.view.types.configurable')

                            @include('shop::products.view.types.grouped')

                            @include('shop::products.view.types.bundle')

                            @include('shop::products.view.types.downloadable')

                            <div class="flex gap-[15px] mt-[30px] max-w-[470px]">

                                {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                                @if ($product->getTypeInstance()->showQuantityBox())
                                    <x-shop::quantity-changer
                                        name="quantity"
                                        value="1"
                                        class="gap-x-[16px] rounded-[12px] py-[15px] px-[26px]"
                                        @change="updateItem($event)"
                                    >
                                    </x-shop::quantity-changer>
                                @endif

                                {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}

                                <button
                                    type="submit"
                                    class="rounded-[12px] border border-navyBlue py-[15px] w-full max-w-full"
                                >
                                    @lang('shop::app.products.add-to-cart')
                                </button>
                            </div>

                            <button
                                class="rounded-[12px] border bg-navyBlue text-white border-navyBlue py-[15px]  w-full max-w-[470px] mt-[20px]"
                                @click='addToCart("buy_now")'
                                {{ ! $product->isSaleable(1) ? 'disabled' : '' }}
                            >
                                @lang('shop::app.products.buy-now')
                            </button>

                            <div class="flex gap-[35px] mt-[40px] max-sm:flex-wrap">
                                <div
                                    class=" flex justify-center items-center gap-[10px] cursor-pointer"
                                    @click='addToCompare()'
                                >
                                    <span class="icon-compare text-[24px]"></span>
                                    @lang('shop::app.products.compare')
                                </div>

                                <div class="flex gap-[25px] max-sm:flex-wrap">
                                    <div class=" flex justify-center items-center gap-[10px] cursor-pointer"><span
                                            class="icon-share text-[24px]"></span>Share</div>
                                    <div class="flex gap-[15px]">
                                        <a href="" class="bg-[position:0px_-274px] bs-main-sprite w-[40px] h-[40px]"
                                            aria-label="Facebook"></a>
                                        <a href="" class="bg-[position:-40px_-274px] bs-main-sprite w-[40px] h-[40px]"
                                            aria-label="Twitter"></a>
                                        <a href="" class="bg-[position:-80px_-274px] bs-main-sprite w-[40px] h-[40px]"
                                            aria-label="Pintrest"></a>
                                        <a href="" class="bg-[position:-120px_-274px] bs-main-sprite w-[40px] h-[40px]"
                                            aria-label="Linkdln"></a>
                                    </div>
                                </div>
                                <!-- Review List Section -->

                                {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </script>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                props: ['productId'],

                data() {
                    return {
                        qty: 1,
                    }
                },

                methods: {
                    updateItem(quantity) {
                        this.qty = quantity;
                    },

                    addToCart(buyNow) {
                        this.$axios.post('{{ route("shop.checkout.cart.store") }}', {
                                product_id: this.productId,
                                quantity: this.qty,
                            })
                            .then(response => {
                                if (response.data.message) {
                                    alert(response.data.message);
                                }
                            })
                            .catch(error => {});
                    },

                    addToWishlist() {
                        this.$axios.post('{{ route("shop.customers.account.wishlist.store", $product->id) }}')
                            .then(response => {
                                alert(response.data.message);
                            })
                            .catch(error => {});
                    },

                    addToCompare() {
                        this.$axios.get('{{ route("shop.customers.account.compare.store", $product->id) }}')
                            .then(response => {
                                alert(response.data.message);
                            })
                            .catch(error => {});
                    },
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>
