<x-admin::layouts>
    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.catalog.categories.index.title')
        </p>

        <a href="{{ route('admin.catalog.categories.create') }}">
            <div class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                @lang('admin::app.catalog.categories.index.add-btn')
            </div>
        </a>
    </div>
</x-admin::layouts>