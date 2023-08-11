<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.products.edit.title')
    </x-slot:title>


    {!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

    <x-admin::form
        method="PUT"
        enctype="multipart/form-data"
    >
        {!! view_render_event('bagisto.admin.catalog.product.edit.actions.before', ['product' => $product]) !!}

        {{-- Page Header --}}
        <div class="grid gap-[10px]">
            <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
                <div class="grid gap-[6px]">
                    <p class="text-[20px] text-gray-800 font-bold leading-[24px]">
                        @lang('admin::app.catalog.products.edit.title')
                    </p>
                </div>

                <div class="flex gap-x-[10px] items-center">
                    <a href="{{ route('admin.catalog.products.index') }}">
                        <span class="px-[12px] py-[6px] border-[2px] border-transparent rounded-[6px] text-gray-600 font-semibold whitespace-nowrap transition-all hover:bg-gray-100 cursor-pointer">
                            @lang('admin::app.account.edit.cancel-btn')
                        </span>
                    </a>

                    <button class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                        @lang('admin::app.catalog.products.edit.save-btn')
                    </button>
                </div>
            </div>
        </div>

        @php
            $channels = core()->getAllChannels();

            $currentChannel = core()->getRequestedChannel();

            $currentLocale = core()->getRequestedLocale();
        @endphp

        {{-- Channel and Locale Switcher --}}
        <div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
            <div class="flex gap-x-[4px] items-center">
                {{-- Channel Switcher --}}
                <x-admin::dropdown :class="$channels->count() <= 1 ? 'hidden' : ''">
                    {{-- Dropdown Toggler --}}
                    <x-slot:toggle>
                        <div class="inline-flex gap-x-[8px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600">
                            <span class="icon-store text-[24px] "></span>
                            
                            {{ $currentChannel->name }}

                            <input type="hidden" name="channel" value="{{ $currentChannel->code }}"/>

                            <span class="icon-sort-down text-[24px]"></span>
                        </div>
                    </x-slot:toggle>

                    {{-- Dropdown Content --}}
                    <x-slot:content class="!p-[0px]">
                        @foreach ($channels as $channel)
                            <a
                                href="?{{ Arr::query(['channel' => $channel->code, 'locale' => $currentLocale->code]) }}"
                                class="flex gap-[10px] px-5 py-2 text-[16px] cursor-pointer hover:bg-gray-100"
                            >
                                {{ $channel->name }}
                            </a>
                        @endforeach
                    </x-slot:content>
                </x-admin::dropdown>

                {{-- Channel Switcher --}}
                <x-admin::dropdown>
                    {{-- Dropdown Toggler --}}
                    <x-slot:toggle>
                        <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600">
                            <span class="icon-language text-[24px] "></span>

                            {{ $currentLocale->name }}
                            
                            <input type="hidden" name="locale" value="{{ $currentLocale->code }}"/>

                            <span class="icon-sort-down text-[24px]"></span>
                        </div>
                    </x-slot:toggle>

                    {{-- Dropdown Content --}}
                    <x-slot:content class="!p-[0px]">
                        @foreach ($currentChannel->locales as $locale)
                            <a
                                href="?{{ Arr::query(['channel' => $currentChannel->code, 'locale' => $locale->code]) }}"
                                class="flex gap-[10px] px-5 py-2 text-[16px] cursor-pointer hover:bg-gray-100 {{ $locale->code == $currentLocale->code ? 'bg-gray-100' : ''}}"
                            >
                                {{ $locale->name }}
                            </a>
                        @endforeach
                    </x-slot:content>
                </x-admin::dropdown>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.product.edit.actions.after', ['product' => $product]) !!}

        <!-- body content -->
        {!! view_render_event('bagisto.admin.catalog.product.edit.form.before', ['product' => $product]) !!}

        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            @foreach ($product->attribute_family->attribute_groups->groupBy('column') as $column => $groups)
                {!! view_render_event('bagisto.admin.catalog.product.edit.form.column_' . $column . '.before', ['product' => $product]) !!}

                <div
                    @if ($column == 1) class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto" @endif
                    @if ($column == 2) class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full" @endif
                >
                    @foreach ($groups as $group)
                        @php
                            $customAttributes = $product->getEditableAttributes($group);
                        @endphp

                        @if (count($customAttributes))
                            {!! view_render_event('bagisto.admin.catalog.product.edit.form..' . $group->name . '.before', ['product' => $product]) !!}

                            <div class="relative p-[16px] bg-white rounded-[4px] box-shadow">
                                <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                                    {{ $group->name }}
                                </p>

                                @if ($group->name == 'Meta Description')
                                    <v-product-seo></v-product-seo>
                                @endif

                                @foreach ($customAttributes as $attribute)
                                    {!! view_render_event('bagisto.admin.catalog.product.edit.form.' . $group->name . '.controls.before', ['product' => $product]) !!}

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label>
                                            {{ $attribute->admin_name . ($attribute->is_required ? '*' : '') }}
                                        </x-admin::form.control-group.label>

                                        @include ('admin::catalog.products.edit.controls', [
                                            'attribute' => $attribute,
                                            'product'   => $product,
                                        ])
            
                                        <x-admin::form.control-group.error :control-name="$attribute->code"></x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    {!! view_render_event('bagisto.admin.catalog.product.edit.form.' . $group->name . '.controls.before', ['product' => $product]) !!}
                                @endforeach

                                @includeWhen($group->name == 'Price', 'admin::catalog.products.edit.price.group')
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.product.edit.form.' . $group->name . '.after', ['product' => $product]) !!}
                        @endif
                    @endforeach

                    @if ($column == 1)
                        {{-- Images View Blade File --}}
                        @include('admin::catalog.products.edit.images')

                        {{-- Videos View Blade File --}}
                        @include('admin::catalog.products.edit.videos')

                        {{-- Product Type View Blade File --}}
                        @includeIf('admin::catalog.products.edit.types.' . $product->type)

                        {{-- Related, Cross Sells, Up Sells View Blade File --}}
                        @include('admin::catalog.products.edit.links')

                        {{-- Include Product Type Additional Blade Files If Any --}}
                        @foreach ($product->getTypeInstance()->getAdditionalViews() as $view)
                            @includeIf($view)
                        @endforeach
                    @else
                        {{-- Inventory View Blade File --}}
                        @includeWhen(! $product->getTypeInstance()->isComposite(), 'admin::catalog.products.edit.inventory')

                        {{-- Categories View Blade File --}}
                        @include('admin::catalog.products.edit.categories')
                    @endif
                </div>

                {!! view_render_event('bagisto.admin.catalog.product.edit.form.column_' . $column . '.after', ['product' => $product]) !!}
            @endforeach
        </div>

        {!! view_render_event('bagisto.admin.catalog.product.edit.form.after', ['product' => $product]) !!}
    </x-admin::form>

    {!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        {{-- SEO Vue Component Template --}}
        <script type="text/x-template" id="v-product-seo-template">
            <div class="flex flex-col gap-[3px] mb-[30px]">
                <p 
                    class="text-[#161B9D]"
                    v-text="metaTitle"
                >
                </p>

                <!-- SEO Meta Title -->
                <p 
                    class="text-[#135F29]"
                    v-text="'{{ URL::to('/') }}/' + (metaTitle ? metaTitle.toLowerCase().replace(/\s+/g, '-') : '')"
                >
                </p>

                <!-- SEP Meta Description -->
                <p 
                    class="text-gray-600"
                    v-text="metaDescription"
                >
                </p>
            </div>
        </script>

        {{-- SEO Vue Component --}}
        <script type="module">
            app.component('v-product-seo', {
                template: '#v-product-seo-template',

                data() {
                    return {
                        metaTitle: this.$parent.getValues()['meta_title'],

                        metaDescription: this.$parent.getValues()['meta_description'],
                    }
                },

                mounted() {
                    let self = this;

                    document.getElementById('meta_title').addEventListener('input', function(e) {
                        self.metaTitle = e.target.value;
                    });

                    document.getElementById('meta_description').addEventListener('input', function(e) {
                        self.metaDescription = e.target.value;
                    });
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>