<x-admin::layouts>
    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.catalog.attributes.index.title')
        </p>

        <a href="{{ route('admin.catalog.attributes.create') }}">
            <div class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                @lang('admin::app.catalog.attributes.index.add')
            </div>
        </a>
    </div>
</x-admin::layouts>