{!! view_render_event('bagisto.shop.categories.view.toolbar.before') !!}

<v-toolbar @filter-applied='setFilters("toolbar", $event)'></v-toolbar>

{!! view_render_event('bagisto.shop.categories.view.toolbar.after') !!}

@inject('toolbar' , 'Webkul\Product\Helpers\Toolbar')

@pushOnce('scripts')
    <script type="text/x-template" id='v-toolbar-template'>
        <div class="flex justify-between max-md:items-center">
            <div class="text-[16px] font-medium hidden max-md:block">
                @lang('shop::app.categories.toolbar.filters')
            </div>

            <!-- Product Sorting Filters -->
            <x-shop::dropdown>
                <x-slot:toggle>
                    <!-- Dropdown Toggler -->
                    <div class="flex justify-between items-center gap-[15px] max-w-[200px] bg-white border border-[#E9E9E9] text-[16px] rounded-lg w-full p-[14px] max-md:pr-[10px] max-md:pl-[10px] max-md:border-0 max-md:w-[110px] cursor-pointer">
                        @{{ sortLabel ?? "@lang('shop::app.products.sort-by.title')" }}

                        <span class="text-[24px] icon-arrow-down"></span>
                    </div>
                </x-slot:toggle>
            
                <!-- Dropdown Content -->
                <x-slot:menu>
                    <x-shop::dropdown.menu.item
                        v-for="(sort, key) in filters.available.sort"
                        ::class="{'bg-gray-100': sort.value == filters.applied.sort}"
                        @click="apply('sort', sort.value)"
                    >
                        @{{ sort.title }}
                    </x-shop::dropdown.menu.item>
                </x-slot:menu>
            </x-shop::dropdown>

            <!-- Product Pagination Limit -->
            <div class="flex gap-[40px] items-center max-md:hidden">
                <!-- Product Pagination Limit -->
                <x-shop::dropdown position="bottom-right">
                    <x-slot:toggle>
                        <!-- Dropdown Toggler -->
                        <div class="flex justify-between items-center gap-[15px] max-w-[200px] bg-white border border-[#E9E9E9] text-[16px] rounded-lg w-full p-[14px] max-md:pr-[10px] max-md:pl-[10px] max-md:border-0 max-md:w-[110px] cursor-pointer">
                            @{{ filters.applied.limit ?? "@lang('shop::app.categories.toolbar.show')" }}

                            <span class="text-[24px] icon-arrow-down"></span>
                        </div>
                    </x-slot:toggle>
                
                    <!-- Dropdown Content -->
                    <x-slot:menu>
                        <x-shop::dropdown.menu.item
                            v-for="(limit, key) in filters.available.limit"
                            ::class="{'bg-gray-100': limit == filters.applied.limit}"
                            @click="apply('limit', limit)"
                        >
                            @{{ limit }}
                        </x-shop::dropdown.menu.item>
                    </x-slot:menu>
                </x-shop::dropdown>

                <!-- Listing Mode Switcher -->
                <div class="flex items-center gap-[20px]">
                    <span
                        class="text-[24px] cursor-pointer"
                        :class="(filters.applied.mode === 'list') ? 'icon-listing-fill' : 'icon-listing'"
                        @click="changeMode('list')"
                    >
                    </span>

                    <span
                        class="text-[24px] cursor-pointer"
                        :class="(filters.applied.mode === 'grid') ? 'icon-grid-view-fill' : 'icon-grid-view'"
                        @click="changeMode()"
                    >
                    </span>
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
                        available: {
                            sort: @json($toolbar->getAvailableOrders()),

                            limit: @json($toolbar->getAvailableLimits()),

                            mode: @json($toolbar->getAvailableModes()),
                        },

                        applied: {
                            sort: '{{ $toolbar->getOrder(isset($params) ? $params : [])['value'] }}',

                            limit: '{{ $toolbar->getLimit(isset($params) ? $params : [] ) }}',

                            mode: '{{ $toolbar->getMode(isset($params) ? $params : [] ) }}',
                        }
                    }
                };
            },

            mounted() {
                this.$emit('filter-applied', this.filters.applied);
            },

            computed: {
                sortLabel() {
                    return this.filters.available.sort.find(sort => sort.value === this.filters.applied.sort).title;
                }
            },

            methods: {
                apply(type, value) {
                    this.filters.applied[type] = value;

                    this.$emit('filter-applied', this.filters.applied);
                },

                changeMode(value = 'grid') {
                    this.filters.applied['mode'] = value;

                    this.$emit('filter-applied', this.filters.applied);
                },
            },
        });
    </script>
@endPushOnce
