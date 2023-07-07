<div class="flex justify-between items-center w-full border border-t-0 border-b-[1px] border-l-0 border-r-0 py-[11px] px-16">
    {{-- currency dropdown --}}
    <x-shop::dropdown>
        <x-slot:toggle>
            <div class="flex">
                <span class="cursor-pointer">{{ core()->getCurrentCurrency()->symbol }}</span>

                <span class="ml-2 cursor-pointer">
                    {{ core()->getCurrentCurrencyCode() }}
                </span>

                <span class="text-[24px] ml-2 icon-arrow-down"></span>
            </div>
        </x-slot:toggle>

        <x-slot:content class="!p-[0px]">
            <v-currency-switcher></v-currency-switcher>
        </x-slot:content>
    </x-shop::dropdown>

    <p class="text-xs font-medium">Get UPTO 40% OFF on your 1st order <a href="#" class="underline">SHOP NOW</a></p>

    {{-- locales dropdown --}}
    <x-shop::dropdown>
        <x-slot:toggle>
            <div class="flex">
                @if (! empty(core()->getCurrentLocale()->image_url))
                    <img 
                        src="{{ core()->getCurrentLocale()->image_url }}"
                        alt="Default locale"
                        width="20"
                        height="20"
                    />
                @else
                    <img 
                        src="{{ asset('/themes/velocity/assets/images/flags/default-locale-image.png') }}"
                        alt="Default locale" 
                        width="20"
                        height="20"
                    />
                @endif
                
                <span class="ml-2 cursor-pointer">
                    {{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') }}
                </span>

                <span class="text-[24px] ml-2 icon-arrow-down"></span>
            </div>
        </x-slot:toggle>
    
        <x-slot:content class="!p-[0px]">
            <v-language-switcher></v-language-switcher>
        </x-slot:content>
    </x-shop::dropdown>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-language-switcher-template">
        <div class="grid gap-[4px] mt-[10px] pb-[10px]">
            @foreach (core()->getCurrentChannel()->locales()->orderBy('name')->get() as $locale)
                <span 
                    class="text-[16px] px-5 py-2 cursor-pointer hover:bg-gray-100 @if($locale->code == app()->getLocale()) bg-gray-100 @endif"
                    @click="set('{{ $locale->code }}')"                    
                >
                    {{ $locale->name }}
                </span>
            @endforeach
        </div>
    </script>

    <script type="module">
        app.component('v-language-switcher', {
            template: '#v-language-switcher-template',

            methods: {
                set(value) {
                    let url = new URL(window.location.href);

                    url.searchParams.set('locale', value);

                    window.location.href = url.href;
                }
            }
        });
    </script>

    <script type="text/x-template" id="v-currency-switcher-template">
        <div class="grid gap-[4px] mt-[10px] pb-[10px]">
            @foreach (core()->getCurrentChannel()->currencies as $currency)
                <span 
                    class="text-[16px] px-5 py-2 cursor-pointer hover:bg-gray-100 @if ($currency->code == core()->getCurrentCurrencyCode()) bg-gray-100 @endif"
                    @click="set('{{ $currency->code }}')"
                >
                    {{ $currency->code }}
                </span >
            @endforeach
        </div>
    </script>

    <script type="module">
        app.component('v-currency-switcher', {
            template: '#v-currency-switcher-template',

            methods: {
                set(value) {
                    let url = new URL(window.location.href);

                    url.searchParams.set('currency', value);

                    window.location.href = url.href;
                }
            }
        });
    </script>
@endPushOnce
