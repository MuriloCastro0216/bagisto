<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.attributes.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <!-- Title -->
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.catalog.attributes.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            @if (bouncer()->hasPermission('catalog.attributes.create'))
                <a href="{{ route('admin.catalog.attributes.create') }}">
                    <div class="primary-button">
                        @lang('admin::app.catalog.attributes.index.create-btn')
                    </div>
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.catalog.attributes.list.before') !!}

    <x-admin::datagrid :src="route('admin.catalog.attributes.index')">
        <template #mass-action="{ available, applied, massActions, validateMassAction, performMassAction }">

        </template>

        <template #search="{ available, applied, search, getSearchedValues }">

        </template>

        <template #filter="{
            available,
            applied,
            filters,
            applyFilter,
            applyColumnValues,
            findAppliedColumn,
            hasAnyAppliedColumnValues,
            getAppliedColumnValues,
            removeAppliedColumnValue,
            removeAppliedColumnAllValues
        }">

        </template>

        <template #header="{ available, applied, isLoading, selectAll, sort, performAction }">

        </template>

        <template #body="{ available, applied, isLoading, selectAll, sort, performAction }">

        </template>

        <template #pagination="{ available, applied, changePage, changePerPageOption }">

        </template>
    </x-admin::datagrid>

    {!! view_render_event('bagisto.admin.catalog.attributes.list.after') !!}

</x-admin::layouts>
