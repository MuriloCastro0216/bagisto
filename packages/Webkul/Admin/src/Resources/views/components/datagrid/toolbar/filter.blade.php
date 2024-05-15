<v-datagrid-filter
    :is-loading="isLoading"
    :available="available"
    :applied="applied"
    :src="src"
    @applyFilter="filter"
    @removeFilter="filter"
    @applySaveFilter="applySaveFilter"
>
    {{ $slot }}
</v-datagrid-filter>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-filter-template"
    >
        <slot
            name="filter"
            :available="available"
            :applied="applied"
            :filters="filters"
            :apply-filter="applyFilter"
            :apply-column-values="applyColumnValues"
            :find-applied-column="findAppliedColumn"
            :has-any-applied-column-values="hasAnyAppliedColumnValues"
            :get-applied-column-values="getAppliedColumnValues"
            :remove-applied-column-value="removeAppliedColumnValue"
            :remove-applied-column-all-values="removeAppliedColumnAllValues"
        >
            <template v-if="isLoading">
                <x-admin::shimmer.datagrid.toolbar.filter />
            </template>

            <template v-else>
                <x-admin::drawer
                    width="350px"
                    ref="filterDrawer"
                >
                    <x-slot:toggle>
                        <div>
                            <div
                                class="relative inline-flex w-full max-w-max cursor-pointer select-none appearance-none items-center justify-between gap-x-1 rounded-md border bg-white px-1 py-1.5 text-center text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:outline-none focus:ring-2 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 ltr:pl-3 ltr:pr-5 rtl:pl-5 rtl:pr-3"
                                :class="{'[&>*]:text-blue-600 [&>*]:dark:text-white': filters.columns.length > 0}"
                            >
                                <span class="icon-filter text-2xl"></span>

                                <span>
                                    @lang('admin::app.components.datagrid.toolbar.filter.title')
                                </span>

                                <span
                                    class="icon-dot absolute right-2 top-1.5 text-sm font-bold"
                                    v-if="filters.columns.length > 0"
                                >
                                </span>
                            </div>

                            <div class="z-10 hidden w-full divide-y divide-gray-100 rounded bg-white shadow dark:bg-gray-900">
                            </div>
                        </div>
                    </x-slot>

                    <x-slot:header>
                        <div class="flex items-center justify-between p-3">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.components.datagrid.filters.title')
                            </p>
                        </div>
                    </x-slot>

                    <x-slot:content class="!p-0">
                        <x-admin::accordion class="!box-shadow-none !rounded-none">
                            <x-slot:header class="px-4 dark:text-white">
                                Quick Filters
                            </x-slot>

                            <x-slot:content class="!p-0">
                                <div class="!p-0">
                                    <ul v-for="(filter,index) in filters.available">
                                        <li 
                                            class="flex cursor-pointer items-center justify-between px-4 py-1.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-white dark:hover:bg-gray-950"  
                                            :class="{'bg-red-500': filter.is_applied == true}"
                                            @click="applySaveFilter(filter)"
                                        >
                                            @{{ filter.name }}
                                        
                                            <span 
                                                class="icon-cross cursor-pointer rounded p-1.5 text-xl hover:bg-gray-200 dark:hover:bg-gray-800"  
                                                @click="deleteSavedFilter(filter)"
                                            >
                                            </span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="mt-4 px-4">
                                    <x-admin::form.control-group.label class="required !text-sm">
                                        @lang('Save filter')
                                    </x-admin::form.control-group.label>

                                    <div class="mb-4 mt-1.5 flex items-center justify-between gap-4">
                                        <x-admin::form.control-group class="!mb-0">
                                            <x-admin::form.control-group.control
                                                type="text"
                                                id="name"
                                                name="name"
                                                class="!px-2 !py-1.5 leading-6" 
                                                rules="required"
                                                :label="trans('name')"
                                                :placeholder="trans('save as')"
                                                v-model="filters.name"
                                            />
            
                                            <x-admin::form.control-group.error control-name="name" />
                                        </x-admin::form.control-group>
                                        
                                        <p 
                                            class="secondary-button"
                                            @click="saveFilters()"
                                        >
                                            @lang('Save Filter')
                                        </p>
                                    </div>
                                </div>
                            </x-slot>
                        </x-admin::accordion>
                                           
                        <x-admin::accordion class="!box-shadow-none !rounded-none">
                            <x-slot:header class="px-4 dark:text-white">
                                Custom Filters
                            </x-slot>

                            <x-slot:content class="!p-0">
                                <div class="!p-5">
                                    <div v-for="column in available.columns">
                                        <div v-if="column.filterable">
                                            <!-- Boolean -->
                                            <div v-if="column.type === 'boolean'">
                                                <div class="flex items-center justify-between">
                                                    <p
                                                        class="text-sm font-medium leading-6 text-gray-800 dark:text-white"
                                                        v-text="column.label"
                                                    >
                                                    </p>
        
                                                    <div
                                                        class="flex items-center gap-x-1.5"
                                                        @click="removeAppliedColumnAllValues(column.index)"
                                                    >
                                                        <p
                                                            class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                            v-if="hasAnyAppliedColumnValues(column.index)"
                                                        >
                                                            @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                                        </p>
                                                    </div>
                                                </div>
        
                                                <div class="mb-2 mt-1.5">
                                                    <x-admin::dropdown>
                                                        <x-slot:toggle>
                                                            <button
                                                                type="button"
                                                                class="inline-flex w-full cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                                            >
                                                                <span
                                                                    class="text-sm text-gray-400 dark:text-gray-400"
                                                                    v-text="'@lang('admin::app.components.datagrid.filters.select')'"
                                                                >
                                                                </span>
        
                                                                <span class="icon-sort-down text-2xl"></span>
                                                            </button>
                                                        </x-slot>
        
                                                        <x-slot:menu>
                                                            <x-admin::dropdown.menu.item
                                                                v-for="option in column.options"
                                                                v-text="option.label"
                                                                @click="applyFilter(option.value, column)"
                                                            >
                                                            </x-admin::dropdown.menu.item>
                                                        </x-slot>
                                                    </x-admin::dropdown>
                                                </div>
        
                                                <div class="mb-4 flex flex-wrap gap-2">
                                                    <p
                                                        class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                        v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                                    >
                                                        <!-- Retrieving the label from the options based on the applied column value. -->
                                                        <span v-text="column.options.find((option => option.value == appliedColumnValue)).label"></span>
        
                                                        <span
                                                            class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                            @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                        >
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
        
                                            <!-- Dropdown -->
                                            <div v-else-if="column.type === 'dropdown'">
                                                <!-- Basic -->
                                                <div v-if="column.options.type === 'basic'">
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-sm font-medium leading-6 text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>
        
                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                                            </p>
                                                        </div>
                                                    </div>
        
                                                    <div class="mb-2 mt-1.5">
                                                        <x-admin::dropdown>
                                                            <x-slot:toggle>
                                                                <button
                                                                    type="button"
                                                                    class="inline-flex w-full cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                                                >
                                                                    <span
                                                                        class="text-sm text-gray-400 dark:text-gray-400"
                                                                        v-text="'@lang('admin::app.components.datagrid.filters.select')'"
                                                                    >
                                                                    </span>
        
                                                                    <span class="icon-sort-down text-2xl"></span>
                                                                </button>
                                                            </x-slot>
        
                                                            <x-slot:menu>
                                                                <x-admin::dropdown.menu.item
                                                                    v-for="option in column.options.params.options"
                                                                    v-text="option.label"
                                                                    @click="applyFilter(option.value, column)"
                                                                >
                                                                </x-admin::dropdown.menu.item>
                                                            </x-slot>
                                                        </x-admin::dropdown>
                                                    </div>
        
                                                    <div class="mb-4 flex flex-wrap gap-2">
                                                        <p
                                                            class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                            v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                                        >
                                                            <!-- Retrieving the label from the options based on the applied column value. -->
                                                            <span v-text="column.options.params.options.find((option => option.value == appliedColumnValue)).label"></span>
        
                                                            <span
                                                                class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                            >
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
        
                                                <!-- Searchable -->
                                                <div v-else-if="column.options.type === 'searchable'">
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-sm font-medium leading-6 text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>
        
                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                                            </p>
                                                        </div>
                                                    </div>
        
                                                    <div class="mb-2 mt-1.5">
                                                        <v-datagrid-searchable-dropdown
                                                            :datagrid-id="available.id"
                                                            :column="column"
                                                            @select-option="applyFilter($event, column)"
                                                        >
                                                        </v-datagrid-searchable-dropdown>
                                                    </div>
        
                                                    <div class="mb-4 flex flex-wrap gap-2">
                                                        <p
                                                            class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                            v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                                        >
                                                            <span v-text="appliedColumnValue"></span>
        
                                                            <span
                                                                class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                            >
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
        
                                            <!-- Date Range -->
                                            <div v-else-if="column.type === 'date_range'">
                                                <div class="flex items-center justify-between">
                                                    <p
                                                        class="text-sm font-medium leading-6 dark:text-white"
                                                        v-text="column.label"
                                                    >
                                                    </p>
        
                                                    <div
                                                        class="flex items-center gap-x-1.5"
                                                        @click="removeAppliedColumnAllValues(column.index)"
                                                    >
                                                        <p
                                                            class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                            v-if="hasAnyAppliedColumnValues(column.index)"
                                                        >
                                                            @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                                        </p>
                                                    </div>
                                                </div>
        
                                                <div class="mt-1.5 grid grid-cols-2 gap-1.5">
                                                    <p
                                                        class="cursor-pointer rounded-md border px-3 py-2 text-center text-sm font-medium leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:text-gray-300 dark:hover:border-gray-400"
                                                        v-for="option in column.options"
                                                        v-text="option.label"
                                                        @click="applyFilter(
                                                            $event,
                                                            column,
                                                            { quickFilter: { isActive: true, selectedFilter: option } }
                                                        )"
                                                    >
                                                    </p>
        
                                                    <x-admin::flat-picker.date ::allow-input="false">
                                                        <input
                                                            value=""
                                                            class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                            :type="column.input_type"
                                                            :name="`${column.index}[from]`"
                                                            :placeholder="column.label"
                                                            :ref="`${column.index}[from]`"
                                                            @change="applyFilter(
                                                                $event,
                                                                column,
                                                                { range: { name: 'from' }, quickFilter: { isActive: false } }
                                                            )"
                                                        />
                                                    </x-admin::flat-picker.date>
        
                                                    <x-admin::flat-picker.date ::allow-input="false">
                                                        <input
                                                            type="column.input_type"
                                                            value=""
                                                            class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                            :name="`${column.index}[to]`"
                                                            :placeholder="column.label"
                                                            :ref="`${column.index}[from]`"
                                                            @change="applyFilter(
                                                                $event,
                                                                column,
                                                                { range: { name: 'to' }, quickFilter: { isActive: false } }
                                                            )"
                                                        />
                                                    </x-admin::flat-picker.date>
        
                                                    <div class="mb-4 flex flex-wrap gap-2">
                                                        <p
                                                            class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                            v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                                        >
                                                            <span v-text="appliedColumnValue.join(' to ')"></span>
        
                                                            <span
                                                                class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                            >
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
        
                                            <!-- Date Time Range -->
                                            <div v-else-if="column.type === 'datetime_range'">
                                                <div class="flex items-center justify-between">
                                                    <p
                                                        class="text-sm font-medium leading-6 dark:text-white"
                                                        v-text="column.label"
                                                    >
                                                    </p>
        
                                                    <div
                                                        class="flex items-center gap-x-1.5"
                                                        @click="removeAppliedColumnAllValues(column.index)"
                                                    >
                                                        <p
                                                            class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                            v-if="hasAnyAppliedColumnValues(column.index)"
                                                        >
                                                            @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                                        </p>
                                                    </div>
                                                </div>
        
                                                <div class="my-4 grid grid-cols-2 gap-1.5">
                                                    <p
                                                        class="cursor-pointer rounded-md border px-3 py-2 text-center text-sm font-medium leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:text-gray-300 dark:hover:border-gray-400"
                                                        v-for="option in column.options"
                                                        v-text="option.label"
                                                        @click="applyFilter(
                                                            $event,
                                                            column,
                                                            { quickFilter: { isActive: true, selectedFilter: option } }
                                                        )"
                                                    >
                                                    </p>
        
                                                    <x-admin::flat-picker.datetime ::allow-input="false">
                                                        <input
                                                            value=""
                                                            class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                            :type="column.input_type"
                                                            :name="`${column.index}[from]`"
                                                            :placeholder="column.label"
                                                            :ref="`${column.index}[from]`"
                                                            @change="applyFilter(
                                                                $event,
                                                                column,
                                                                { range: { name: 'from' }, quickFilter: { isActive: false } }
                                                            )"
                                                        />
                                                    </x-admin::flat-picker.datetime>
        
                                                    <x-admin::flat-picker.datetime ::allow-input="false">
                                                        <input
                                                            type="column.input_type"
                                                            value=""
                                                            class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                            :name="`${column.index}[to]`"
                                                            :placeholder="column.label"
                                                            :ref="`${column.index}[from]`"
                                                            @change="applyFilter(
                                                                $event,
                                                                column,
                                                                { range: { name: 'to' }, quickFilter: { isActive: false } }
                                                            )"
                                                        />
                                                    </x-admin::flat-picker.datetime>
        
                                                    <div class="mb-4 flex flex-wrap gap-2">
                                                        <p
                                                            class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                            v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                                        >
                                                            <span v-text="appliedColumnValue.join(' to ')"></span>
        
                                                            <span
                                                                class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                            >
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
        
                                            <!-- Rest -->
                                            <div v-else>
                                                <div class="flex items-center justify-between">
                                                    <p
                                                        class="text-sm font-medium leading-6 dark:text-white"
                                                        v-text="column.label"
                                                    >
                                                    </p>
        
                                                    <div
                                                        class="flex items-center gap-x-1.5"
                                                        @click="removeAppliedColumnAllValues(column.index)"
                                                    >
                                                        <p
                                                            class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                            v-if="hasAnyAppliedColumnValues(column.index)"
                                                        >
                                                            @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                                        </p>
                                                    </div>
                                                </div>
        
                                                <div class="mb-2 mt-1.5 grid">
                                                    <input
                                                        type="text"
                                                        class="block w-full rounded-md border bg-white px-2 py-1.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                                        :name="column.index"
                                                        :placeholder="column.label"
                                                        @keyup.enter="applyFilter($event, column)"
                                                    />
                                                </div>
        
                                                <div class="mb-4 flex flex-wrap gap-2">
                                                    <p
                                                        class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                        v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                                    >
                                                        <span v-text="appliedColumnValue"></span>
        
                                                        <span
                                                            class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                            @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                        >
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </x-slot>
                        </x-admin::accordion>
                    </x-slot>
                </x-admin::drawer>
            </template>
        </slot>
    </script>

    <script type="module">
        app.component('v-datagrid-filter', {
            template: '#v-datagrid-filter-template',

            props: ['isLoading', 'available', 'applied', 'src'],

            data() {
                return {
                    savedFilters: {
                        available: [],
                    },

                    filters: {
                        columns: [],

                        applied: null,

                        name: '',
                    },
                };
            },

            mounted() {
                this.filters.columns = this.applied.filters.columns.filter((column) => column.index !== 'all');

                this.getFilters();
            },

            methods: {
                /**
                 * Save filters to the database.
                 *
                 * @returns {void}
                 */
                saveFilters() {
                    this.$axios.post('{{ route('datagrid.filters.store') }}', {
                        user_id: {{ auth()->guard('admin')->user()->id }},
                        src: this.src,
                        name: this.filters.name,
                        applied: this.applied,
                     })
                        .then(response => {
                            this.filters.available.push(response.data.data);
                            
                            this.filters.name = '';

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', { type: 'error', message: response.data.message });
                        });
                },

                /**
                 * Applies the saved filter.
                 *
                 * @param {Object} filter - The filter to be applied.
                 * @emits applySaveFilter - Event emitted when the saved filter is applied.
                 */
                applySaveFilter(filter) {
                    this.$emit('applySaveFilter', filter);
                },

                /**
                 * Retrieves the filters updates the available filters.
                 */
                getFilters () {
                    this.$axios.get('{{ route('datagrid.filters.index') }}', {
                        params: {src: "{{request()->url()}}" }
                    })
                        .then(response => {
                            this.filters.available = response.data;
                        })
                        .catch(error => {
                        });
                },

                /**
                 * Deletes the saved filter.
                 */
                deleteSavedFilter(filter) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.$axios.delete('{{ route('datagrid.filters.destroy') }}', {
                                params: {
                                    id: filter.id,
                                    user_id: filter.user_id
                                }
                            })

                            .then(response => {
                                let index = this.filters.available.indexOf(filter);

                                this.filters.available.splice(index, 1);

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: response.data.message });
                            });
                        }
                    });
                },

                /**
                 * Apply filter.
                 *
                 * @param {Event} $event
                 * @param {object} column
                 * @param {object} additional
                 * @returns {void}
                 */
                applyFilter($event, column = null, additional = {}) {
                    let quickFilter = additional?.quickFilter;

                    if (quickFilter?.isActive) {
                        let options = quickFilter.selectedFilter;

                        switch (column.type) {
                            case 'date_range':
                            case 'datetime_range':
                                this.applyColumnValues(column, options.from, {
                                    range: {
                                        name: 'from'
                                    }
                                });

                                this.applyColumnValues(column, options.to, {
                                    range: {
                                        name: 'to'
                                    }
                                });

                                break;

                            default:
                                break;
                        }
                    } else {
                        /**
                         * Here, either a real event will come or a string value. If a string value is present, then
                         * we create a similar event-like structure to avoid any breakage and make it easy to use.
                         */
                        if ($event?.target?.value === undefined) {
                            $event = {
                                target: {
                                    value: $event,
                                }
                            };
                        }

                        this.applyColumnValues(column, $event.target.value, additional);

                        if (column) {
                            $event.target.value = '';
                        }
                    }

                    this.$emit('applyFilter', this.filters);

                    this.$refs.filterDrawer.close();
                },

                /**
                 * Apply column values.
                 *
                 * @param {object} column
                 * @param {string} requestedValue
                 * @param {object} additional
                 * @returns {void}
                 */
                applyColumnValues(column, requestedValue, additional = {}) {
                    let appliedColumn = this.findAppliedColumn(column?.index);

                    if (
                        requestedValue === undefined ||
                        requestedValue === '' ||
                        appliedColumn?.value.includes(requestedValue)
                    ) {
                        return;
                    }

                    switch (column.type) {
                        case 'date_range':
                        case 'datetime_range':
                            let { range } = additional;

                            if (appliedColumn) {
                                let appliedRanges = appliedColumn.value[0];

                                if (range.name == 'from') {
                                    appliedRanges[0] = requestedValue;
                                }

                                if (range.name == 'to') {
                                    appliedRanges[1] = requestedValue;
                                }

                                appliedColumn.value = [appliedRanges];
                            } else {
                                let appliedRanges = ['', ''];

                                if (range.name == 'from') {
                                    appliedRanges[0] = requestedValue;
                                }

                                if (range.name == 'to') {
                                    appliedRanges[1] = requestedValue;
                                }

                                this.filters.columns.push({
                                    ...column,
                                    value: [appliedRanges]
                                });
                            }

                            break;

                        default:
                            if (appliedColumn) {
                                appliedColumn.value.push(requestedValue);
                            } else {
                                this.filters.columns.push({
                                    ...column,
                                    value: [requestedValue]
                                });
                            }

                            break;
                    }
                },

                /**
                 * Find applied column.
                 *
                 * @param {string} columnIndex
                 * @returns {object}
                 */
                findAppliedColumn(columnIndex) {
                    return this.filters.columns.find(column => column.index === columnIndex);
                },

                /**
                 * Check if any values are applied for the specified column.
                 *
                 * @param {string} columnIndex
                 * @returns {boolean}
                 */
                hasAnyAppliedColumnValues(columnIndex) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    return appliedColumn?.value.length > 0;
                },

                /**
                 * Get applied values for the specified column.
                 *
                 * @param {string} columnIndex
                 * @returns {Array}
                 */
                getAppliedColumnValues(columnIndex) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    return appliedColumn?.value ?? [];
                },

                /**
                 * Remove a specific value from the applied values of the specified column.
                 *
                 * @param {string} columnIndex
                 * @param {any} appliedColumnValue
                 * @returns {void}
                 */
                removeAppliedColumnValue(columnIndex, appliedColumnValue) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    appliedColumn.value = appliedColumn?.value.filter(value => value !== appliedColumnValue);

                    /**
                     * Clean up is done here. If there are no applied values present, there is no point in including the applied column as well.
                     */
                    if (! appliedColumn.value.length) {
                        this.filters.columns = this.filters.columns.filter(column => column.index !== columnIndex);
                    }

                    this.$emit('removeFilter', this.filters);

                    this.$refs.filterDrawer.close();
                },

                /**
                 * Remove all values from the applied values of the specified column.
                 *
                 * @param {string} columnIndex
                 * @returns {void}
                 */
                removeAppliedColumnAllValues(columnIndex) {
                    this.filters.columns = this.filters.columns.filter(column => column.index !== columnIndex);

                    this.$emit('removeFilter', this.filters);
                },
            },
        });
    </script>

    <script type="text/x-template" id="v-datagrid-searchable-dropdown-template">
        <x-admin::dropdown ::close-on-click="false">
            <x-slot:toggle>
                <button
                    type="button"
                    class="inline-flex w-full cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                >
                    <span
                        class="text-sm text-gray-400 dark:text-gray-400"
                        v-text="'@lang('admin::app.components.datagrid.filters.select')'"
                    >
                    </span>

                    <span class="icon-sort-down text-2xl"></span>
                </button>
            </x-slot>

            <x-slot:menu>
                <div class="relative">
                    <div class="relative rounded">
                        <ul class="list-reset">
                            <li class="p-2">
                                <input
                                    class="block w-full rounded-md border bg-white px-2 py-1.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                    @keyup="lookUp($event)"
                                >
                            </li>

                            <ul class="p-2">
                                <li v-if="! isMinimumCharacters">
                                    <p
                                        class="block p-2 text-gray-600 dark:text-gray-300"
                                        v-text="'@lang('admin::app.components.datagrid.filters.dropdown.searchable.atleast-two-chars')'"
                                    >
                                    </p>
                                </li>

                                <li v-else-if="! searchedOptions.length">
                                    <p
                                        class="block p-2 text-gray-600 dark:text-gray-300"
                                        v-text="'@lang('admin::app.components.datagrid.filters.dropdown.searchable.no-results')'"
                                    >
                                    </p>
                                </li>

                                <li
                                    v-for="option in searchedOptions"
                                    v-else
                                >
                                    <p
                                        class="cursor-pointer p-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"
                                        v-text="option.label"
                                        @click="selectOption(option)"
                                    >
                                    </p>
                                </li>
                            </ul>
                        </ul>
                    </div>
                </div>
            </x-slot>
        </x-admin::dropdown>
    </script>

    <script type="module">
        app.component('v-datagrid-searchable-dropdown', {
            template: '#v-datagrid-searchable-dropdown-template',

            props: ['datagridId', 'column'],

            data() {
                return {
                    isMinimumCharacters: false,

                    searchedOptions: [],
                };
            },

            methods: {
                /**
                 * Perform a look up for options based on the search query.
                 *
                 * @param {Event} $event
                 * @returns {void}
                 */
                lookUp($event) {
                    let params = {
                        datagrid_id: this.datagridId,
                        column: this.column.index,
                        search: $event.target.value,
                    };

                    if (! (params['search'].length > 1)) {
                        this.searchedOptions = [];

                        this.isMinimumCharacters = false;

                        return;
                    }

                    this.$axios
                        .get('{{ route('admin.datagrid.look_up') }}', {
                            params
                        })
                        .then(({
                            data
                        }) => {
                            this.isMinimumCharacters = true;

                            this.searchedOptions = data;
                        });
                },

                /**
                 * Select an option from the searched options.
                 *
                 * @param {object} option
                 * @returns {void}
                 */
                selectOption(option) {
                    this.searchedOptions = [];

                    this.$emit('select-option', {
                        target: {
                            value: option.value
                        }
                    });
                },
            },
        });
    </script>
@endpushOnce
