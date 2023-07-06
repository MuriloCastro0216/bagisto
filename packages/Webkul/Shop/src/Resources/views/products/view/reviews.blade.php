{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}

<v-product-review :product-id="{{ $product->id }}"></v-product-review>

@pushOnce('scripts')
    {{-- Product Review Template --}}
    <script type="text/x-template" id="v-product-review-template">
        <div class="container max-1180:px-[20px]">
            <!-- Create Review Form Container -->
            <div class="w-full" v-if="canReview">
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form
                        class="grid grid-cols-[auto_1fr] max-md:grid-cols-[1fr] gap-[40px] justify-center"
                        @submit="handleSubmit($event, store)"
                        enctype="multipart/form-data"
                    >
                        <div class="max-w-[286px]">
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.control
                                    type="image"
                                    name="attachments"
                                    class="!p-0 !mb-0"
                                    rules="required"
                                    :label="trans('shop::app.products.view.reviews.attachments')"
                                    :is-multiple="true"
                                >
                                </x-shop::form.control-group.control>

                                <x-shop::form.control-group.error
                                    class="mt-4"
                                    control-name="attachments"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>
                        </div>
                        
                        <div>
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label>
                                    @lang('shop::app.products.rating')
                                </x-shop::form.control-group.label>

                                <x-shop::products.star-rating
                                    name="rating"
                                    :value="old('rating') ?? 5"
                                    :disabled="false"
                                    rules="required"
                                    :label="trans('shop::app.products.rating')"
                                >
                                </x-shop::products.star-rating>

                                <x-shop::form.control-group.error
                                    control-name="rating"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label>
                                    @lang('shop::app.products.title')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="text"
                                    name="title"
                                    :value="old('title')"
                                    rules="required"
                                    :label="trans('shop::app.products.title')"
                                    :placeholder="trans('shop::app.products.title')"
                                >
                                </x-shop::form.control-group.control>

                                <x-shop::form.control-group.error
                                    control-name="title"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label>
                                    @lang('shop::app.products.comment')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="textarea"
                                    rows="12"
                                    name="comment"
                                    :value="old('comment')"
                                    rules="required"
                                    :label="trans('shop::app.products.comment')"
                                    :placeholder="trans('shop::app.products.comment')"
                                >
                                </x-shop::form.control-group.control>

                                <x-shop::form.control-group.error
                                    control-name="comment"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>


                            <div class="flex justify-start gap-[15px] max-sm:flex-wrap mt-4">
                                <button
                                    class="w-full bg-navyBlue text-white text-[16px] max-w-[374px] font-medium py-[16px] px-[43px] rounded-[18px] text-center"
                                    type='submit'
                                >
                                    @lang('shop::app.products.submit-review')
                                </button>
                                
                                <button
                                    type="button"
                                    class="flex gap-x-[15px] items-center border border-navyBlue px-[30px] py-[10px] cursor-pointer rounded-[18px]"
                                    @click="canReview = false"
                                >
                                    @lang('shop::app.products.view.reviews.cancel')
                                </button>
                            </div>
                        </div>
                    </form>
                </x-shop::form>
            </div>

            <!-- Product Reviews Container -->
            <div v-else>
                <div class="flex items-center justify-between gap-[15px] max-sm:flex-wrap">
                    <h3 class="font-dmserif text-[30px] max-sm:text-[22px]">
                        @lang('shop::app.products.customer-review')
                    </h3>

                    <div
                        class="flex gap-x-[15px] items-center rounded-[12px] border border-navyBlue px-[15px] py-[10px] cursor-pointer"
                        @click="canReview = true"
                    >
                        <span class="icon-pen text-[24px]"></span>

                        @lang('shop::app.products.write-a-review')
                    </div>
                </div>

                <div class="flex justify-between items-center gap-[15px] mt-[30px] max-w-[365px] max-sm:flex-wrap">
                    <p class="text-[30px] font-medium max-sm:text-[16px]">{{ number_format($avgRatings, 1) }}</p>

                    <x-shop::products.star-rating :value="$avgRatings"></x-shop::products.star-rating>

                    <p class="text-[12px] text-[#858585]">
                        (@{{ meta.total }} @lang('shop::app.products.customer-review'))
                    </p>
                </div>

                <div class="flex gap-x-[20px] items-center">
                    <div class="flex gap-y-[18px] max-w-[365px] mt-[10px] flex-wrap">
                        @for ($i = 5; $i >= 1; $i--)
                            <div class="flex gap-x-[25px] items-center max-sm:flex-wrap">
                                <div class="text-[16px] font-medium">{{ $i }} Stars</div>
                                <div class="h-[16px] w-[275px] max-w-full bg-[#E5E5E5] rounded-[2px]">
                                    <div class="h-[16px] bg-[#FEA82B] rounded-[2px]" style="width: {{ $percentageRatings[$i] }}%"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="grid grid-cols-[1fr_1fr] mt-[60px] gap-[20px] max-1060:grid-cols-[1fr]">
                    <!-- Product Review Item Vue Component -->
                    <v-product-review-item
                        v-for='review in reviews'
                        :review="review"
                    ></v-product-review-item>
                </div>

                <button
                    class="block mx-auto text-navyBlue text-base w-max font-medium py-[11px] px-[43px] border rounded-[18px] border-navyBlue bg-white mt-[60px] text-center"
                    v-if="links?.next"
                    @click="get()"
                >
                    @lang('shop::app.products.load-more')
                </button>
            </div>
        </div>
    </script>

    {{-- Product Review Item Template --}}
    <script type="text/x-template" id="v-product-review-item-template">
        <div class="flex gap-[20px] border border-[#e5e5e5] rounded-[12px] p-[25px] max-sm:flex-wrap">
            <div>
                <div
                    class="flex justify-center items-center rounded-[12px] bg-[#F5F5F5] min-h-[100px] max-h-[100px] min-w-[100px] max-w-[100px] max-sm:hidden"
                    :title="review.name"
                >
                    <span
                        class="font-semibold text-[24px] text-[#7D7D7D]"
                        v-text="review.name.split(' ').map(name => name.charAt(0).toUpperCase()).join('')"
                    >
                    </span>
                </div>
            </div>

            <div class="w-full">
                <div class="flex justify-between">
                    <p
                        class="text-[20px] font-medium max-sm:text-[16px]"
                        v-text="review.name"
                    >
                    </p>

                    <div class="flex items-center">
                        <x-shop::products.star-rating 
                            ::name="review.name" 
                            ::value="review.rating"
                        >
                        </x-shop::products.star-rating>
                    </div>
                </div>

                <p
                    class="text-[14px] font-medium mt-[10px] max-sm:text-[12px]"
                    v-text="review.created_at"
                >
                </p>

                <p
                    class="text-[16px] text-[#7D7D7D] font-semibold mt-[20px] max-sm:text-[12px]"
                    v-text="review.title"
                >
                </p>

                <p
                    class="text-[16px] text-[#7D7D7D] mt-[20px] max-sm:text-[12px]"
                    v-text="review.comment"
                >
                </p>

                <div class="flex flex-wrap gap-2 mt-2">
                    <template v-for="file in review.images">
                        <a
                            :href="file.url"
                            class="h-12 w-12 flex"
                            target="_blank"
                            v-if="file.type == 'image'"
                        >

                            <img
                                class="rounded-[12px] min-w-[50px] max-h-[50px] cursor-pointer"
                                :src="file.url"
                                :alt="review.name"
                                :title="review.name"
                            >
                        </a>

                        <a
                            :href="file.url"
                            class="h-12 w-12 flex"
                            target="_blank"
                            v-else
                        >
                            <video
                                class="rounded-[12px] min-w-[50px] max-h-[50px] cursor-pointer"
                                :src="file.url"
                                :alt="review.name"
                                :title="review.name"
                            >
                            </video>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product-review', {
            template: '#v-product-review-template',

            props: ['productId'],

            data() {
                return {
                    canReview: false,

                    reviews: [],

                    links: {
                        next: '{{ route("shop.api.products.reviews.index", $product->id) }}',
                    },

                    meta: {},
                }
            },

            mounted() {
                this.get();
            },

            methods: {
                get() {
                    if (this.links?.next) {
                        this.$axios.get(this.links.next)
                            .then(response => {
                                this.reviews = [...this.reviews, ...response.data.data];

                                this.links = response.data.links;

                                this.meta = response.data.meta;
                            })
                            .catch(error => {});
                    }
                },

                store(params, { resetForm }) {
                    this.$axios.post('{{ route("shop.api.products.reviews.store", $product->id) }}', params, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(response => {
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                            resetForm();

                            this.canReview = false;
                        })
                        .catch(error => {});
                },

                selectReviewImage() {
                    this.reviewImage = event.target.files[0];
                },
            },
        });
        
        app.component('v-product-review-item', {
            template: '#v-product-review-item-template',

            props: ['review'],
        });
    </script>
@endPushOnce

{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}
