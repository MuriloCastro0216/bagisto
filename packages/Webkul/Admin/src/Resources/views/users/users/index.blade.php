<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.users.users.index.title')
    </x-slot:title>

    <v-create-user-form>
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.users.users.index.title')
            </p>
    
            <div class="flex gap-x-[10px] items-center">
                {{-- Create User Button --}}
                <button
                    type="button"
                    class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                >
                    @lang('admin::app.users.users.index.create.title')
                </button>
            </div>
        </div>

        {{-- DataGrid Shimmer --}}
        <x-admin::shimmer.datagrid></x-admin::shimmer.datagrid>
    </v-create-user-form>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-user-form-template">

            <div class="flex justify-between items-center">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('admin::app.users.users.index.title')
                </p>

                <div class="flex gap-x-[10px] items-center">
                    <!-- User Create Button -->
                    @if (bouncer()->hasPermission('settings.users.users.create'))
                        <button
                            type="button"
                            class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                            @click="$refs.customerCreateModal.open()"
                        >
                            @lang('admin::app.users.users.index.create.title')
                        </button>
                    @endif
                </div>
            </div>

            <!-- Datagrid -->
            <x-admin::datagrid
                src="{{ route('admin.users.index') }}"
                ref="datagrid"
            >
                <!-- DataGrid Header -->
                <template #header="{ columns, records, sortPage}">
                    <div class="row grid grid-cols-6 grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] text-gray-600 bg-gray-50 font-semibold">
                        <!-- ID -->
                        <div
                            class="flex gap-[10px] cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'id'))"
                        >
                            <p class="text-gray-600">
                                @lang('admin::app.users.users.index.datagrid.id')
                            </p>
                        </div>

                        <!-- Name -->
                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'name'))"
                        >
                            <p class="text-gray-600">
                                @lang('admin::app.users.users.index.datagrid.name')
                            </p>
                        </div>

                        <!-- Status -->
                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'status'))"
                        >
                            <p class="text-gray-600">
                                @lang('admin::app.users.users.index.datagrid.status')
                            </p>
                        </div>

                        <!-- Email -->
                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'email'))"
                        >
                            <p class="text-gray-600">
                                @lang('admin::app.users.users.index.datagrid.email')
                            </p>
                        </div>

                        <!-- Role -->
                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'role'))"
                        >
                            <p class="text-gray-600">
                                @lang('admin::app.users.users.index.datagrid.role')
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="cursor-pointer flex justify-end">
                            <p class="text-gray-600">
                                @lang('admin::app.users.users.index.datagrid.actions')
                            </p>
                        </div>
                    </div>
                </template>

                <!-- DataGrid Body -->
                <template #body="{ columns, records }">
                    <div
                        v-for="record in records"
                        class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] border-gray-300 text-gray-600 transition-all hover:bg-gray-100"
                        style="grid-template-columns: repeat(6, 1fr);"
                    >
                        <!-- Id -->
                        <p v-text="record.user_id"></p>

                        <!-- User Profile -->
                        <p>
                            <div class="flex gap-[10px] items-center">
                                <div class="inline-block w-[36px] h-[36px] rounded-full border-3 border-gray-800 align-middle text-center mr-2 overflow-hidden">
                                    <img
                                        class="w-[36px] h-[36px]"
                                        :src="record.user_img"
                                        alt="record.user_name"
                                    />
                                </div>
        
                                <div
                                    class="text-sm"
                                    v-text="record.user_name"
                                >
                                </div> 
                            </div>
                        </p>

                        <!-- Status -->
                        <p v-text="record.status"></p>

                        <!-- Email -->
                        <p v-text="record.email"></p>

                        <!-- Role -->
                        <p v-text="record.role_name"></p>

                        <!-- Actions -->
                        <div class="flex justify-end">
                            <a @click="id=1; editModal(record.user_id)">
                                <span
                                    :class="record.actions['0'].icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100 max-sm:place-self-center"
                                    :title="record.actions['0'].title"
                                >
                                </span>
                            </a>

                            <a @click="deleteModal(record.actions['1']?.url)">
                                <span
                                    :class="record.actions['1'].icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100 max-sm:place-self-center"
                                    :title="record.actions['1'].title"
                                >
                                </span>
                            </a>
                        </div>
                    </div>
                </template>
            </x-admin::datagrid>

            <!-- Modal Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form @submit="handleSubmit($event, create)">
                    <!-- User Create Modal -->
                    <x-admin::modal ref="customerCreateModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-[18px] text-gray-800 font-bold">
                                @lang('admin::app.users.users.index.create.title')
                            </p>    
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                <!-- Name -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.users.users.index.create.name')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        id="name"
                                        rules="required"
                                        :label="trans('admin::app.users.users.index.create.name')" 
                                        :placeholder="trans('admin::app.users.users.index.create.name')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Email -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.users.users.index.create.email')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="email"
                                        name="email"
                                        id="email"
                                        rules="required|email"
                                        label="{{ trans('admin::app.users.users.index.create.email') }}"
                                        placeholder="email@example.com"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="email"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Password -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.users.users.index.create.password')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="password"
                                        name="password"
                                        id="password" 
                                        ref="password"
                                        rules="required|min:6"
                                        :label="trans('admin::app.users.users.index.create.password')"
                                        :placeholder="trans('admin::app.users.users.index.create.password')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="password"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Confirm Password -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.users.users.index.create.confirm-password')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation" 
                                        rules="confirmed:@password"
                                        :label="trans('admin::app.users.users.index.create.password')"
                                        :placeholder="trans('admin::app.users.users.index.create.confirm-password')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="password_confirmation"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Role -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.users.users.index.create.role')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="role_id"
                                        rules="required"
                                        :label="trans('admin::app.users.users.index.create.role')"
                                        :placeholder="trans('admin::app.users.users.index.create.role')"
                                    >
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="role_id"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Status -->
                                <x-admin::form.control-group class="!mb-[0px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.users.users.index.create.status')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="status"
                                        :value="1"
                                        :checked="old('status')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="status"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </x-slot:content>
        
                        <x-slot:footer>
                            <!-- Modal Submission -->
                            <div class="flex gap-x-[10px] items-center">
                                <button 
                                    type="submit"
                                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('admin::app.users.users.index.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-create-user-form', {
                template: '#v-create-user-form-template',

                methods: {
                    create(params, { resetForm, setErrors }) {
                        this.$axios.post("{{ route('admin.users.store') }}", params)
                            .then((response) => {
                                this.$refs.customerCreateModal.close();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(id) {
                        this.$axios.get(`{{ route('admin.users.edit', '') }}/${id}`)
                            .then((response) => {
                                let values = {
                                    id: response.data.data.user.id,
                                    name: response.data.data.user.name,
                                    email: response.data.data.user.email,
                                    status: response.data.data.user.status,
                                };

                                this.$refs.customerCreateModal.toggle();

                                this.$refs.modalForm.setValues(values);
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>