
{!! view_render_event('bagisto.shop.categories.view.toolbar.before') !!}

<v-toolbar @onFilterApplied='setFilters("toolbar", $event)'></v-toolbar>

{!! view_render_event('bagisto.shop.categories.view.toolbar.after') !!}

@inject('toolbar' , 'Webkul\Product\Helpers\Toolbar')

@pushOnce('scripts')
    <script type="text/x-template" id='v-toolbar-template'>
        <div class="flex justify-between max-md:items-center">
            <div class="text-[16px] font-medium hidden max-md:block">Filters</div>

            <div>
                <select 
                    class="custom-select max-w-[200px] bg-white border border-[#E9E9E9] text-[16px] rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-[14px] pr-[36px]  max-md:border-0 max-md:outline-none max-md:w-[110px]"
                    v-model="filters.applied.sort"
                    @change="apply('sort', filters.applied.sort)"
                >
                    <option value=''>Sort by</option>
                    <option :value="key" v-for="(sort, key) in filters.available.sort">
                        @{{ sort }}
                    </option>
                </select>
            </div>

            <div class="flex gap-[40px] items-center max-md:hidden">
                <select 
                    class="custom-select max-w-[120px] bg-white border border-[#E9E9E9] text-[16px] rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-[14px] pr-[36px]"
                    v-model="filters.applied.limit"
                    @change="apply('limit', filters.applied.limit)"
                >
                    <option value=''>Show</option>
                    <option :value="limit" v-for="limit in filters.available.limit">
                        @{{ limit }}
                    </option>
                </select>

                <div class="flex items-center gap-[20px]">
                    <span class="icon-listing text-[24px]"></span>
                    <span class="icon-grid-view text-[24px]"></span>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-toolbar', {
            template: '#v-toolbar-template',

            data() {
                return {
                    filters: {
                        queryParams: @json(request()->input()),

                        available: {
                            sort: @json($toolbar->getAvailableOrders()),

                            limit: @json($toolbar->getAvailableLimits()),
                        },

                        applied: {
                            sort: "{{ (core()->getConfigData('catalog.products.storefront.sort_by') ?? 'price-desc') }}",

                            limit: "{{ request()->query('limit') ?? 12 }}",
                        }
                    }
                };
            },

            mounted() {
                this.$emit('onFilterApplied', this.filters.applied);
            },

            methods: {
                apply(type, value) {
                    this.filters.applied[type] = value;

                    this.$emit('onFilterApplied', this.filters.applied);
                }
            },
        });
    </script>
@endPushOnce