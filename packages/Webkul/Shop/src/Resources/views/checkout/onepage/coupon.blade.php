<v-coupon 
    :is-coupon-applied="cart.coupon_code"
    :sub-total="cart.base_grand_total"
>
</v-coupon>

@pushOnce('scripts')
    <script type="text/x-template" id="v-coupon-template">
        <div class="flex text-right justify-between">
            <p class="text-[16px] max-sm:text-[14px] max-sm:font-normal">@lang('shop::app.checkout.cart.coupon.discount')</p>
            
            <p class="text-[16px] max-sm:text-[14px] max-sm:font-medium font-medium cursor-pointer">
                <x-shop::modal>
                    <x-slot:toggle>
                        <span class="text-navyBlue">
                            @lang('shop::app.checkout.cart.coupon.apply')
                        </span>
                    </x-slot:toggle>

                    <x-slot:header>
                        <h2 class="text-[25px] font-medium max-sm:text-[22px]">Apply Coupon</h2>
                    </x-slot:header>

                    <x-slot:content>
                       
                        <x-shop::form
                            v-slot="{ meta, errors, handleSubmit }"
                            as="div"
                        >
                            <form @submit="handleSubmit($event, applyCoupon)">
                                <x-shop::form.control-group>
                                    <div class="p-[30px] bg-white">
                                        <x-shop::form.control-group.control
                                            type="text"
                                            name="code"
                                            class="text-[14px] appearance-none border rounded-[12px] w-full py-[20px] px-[25px] focus:outline-none focus:shadow-outline"
                                            placeholder="Enter your code"
                                            rules="required"
                                            v-model="code"
                                        >
                                        </x-shop::form.control-group.control>

                                        <x-shop::form.control-group.error
                                            control-name="code"
                                        >
                                        </x-shop::form.control-group.error>
                                    </div>
                                </x-shop::form.control-group>

                                <div class="max-h-[150px] overflow-y-auto">
                                    <div 
                                        class="p-[30px] bg-white"
                                        v-for="coupon in coupons"
                                    >
                                        <div class="select-none flex gap-x-[15px] items-center">
                                            <v-field
                                                type="radio"
                                                name="coupon_code"
                                                :id="coupon.code"
                                                class="hidden peer"
                                                @change="assignCoupon(coupon)"
                                            ></v-field>

                                            <label
                                                class="icon-radio-unselect text-[24px] text-navyBlue right-[20px] top-[20px] peer-checked:icon-radio-select cursor-pointer"
                                                :for="coupon.code"
                                            ></label>

                                            <label 
                                                class="px-[25px] py-[10px] border-dotted border-navyBlue border-2 rounded-[12px] max-w-max cursor-pointer"
                                                :for="coupon.code"
                                            >
                                                @{{ coupon.name }}
                                            </label>
                                        </div>

                                        <p class="text-[14px] font-medium text-[#7D7D7D] max-w-[255px] mt-[15px]">@{{ coupon.description }}</p>
                                    </div>
                                </div>

                                <div class="p-[30px] bg-white mt-[20px]">
                                    <div class="flex justify-between items-center gap-[15px] flex-wrap">
                                        <p class="text-[14px] font-medium text-[#7D7D7D]">@lang('Subtotal')</p>
                                        <div class="flex gap-[30px] items-center flex-auto flex-wrap">
                                            <p class="text-[30px] font-semibold max-sm:text-[22px]">@{{ subTotal }}</p>
                                            <button
                                                class="block flex-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer max-sm:text-[14px] max-sm:px-[25px]"
                                                type="submit"
                                            >
                                              @lang('shop::app.customers.account.save')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </x-shop::form>
                    </x-slot:content>
                </x-shop::modal>

                <div 
                    class="text-[12px] font-small flex justify-between items-center"
                    v-if="isCouponApplied"
                >
                    <p class="text-[12px] mr-2">@lang('Coupon applied')</p>
                    
                    <p 
                        class="text-[16px] font-medium cursor-pointer text-navyBlue"
                        title="@lang('Applied coupon')"
                    >
                        "@{{ isCouponApplied }}"
                    </p>

                    <span 
                        class="icon-cancel text-[30px] cursor-pointer"
                        title="@lang('Remove coupon')"
                        @click="destroyCoupon"
                    >
                    </span>
                </div>
            </p>
        </div>
    </script>

    <script type="module">
        app.component('v-coupon', {
            template: '#v-coupon-template',
            
            props: ['isCouponApplied', 'subTotal'],

            data() {
                return {
                    coupons: [],

                    code: '',
                }
            },

            created() {
                this.getAllCoupons();
            },

            methods: {
                applyCoupon(params) {
                    this.$axios.post("{{ route('shop.checkout.cart.coupon.apply') }}", params)
                        .then((response) => {
                            alert(response.data.data.message);

                            this.$parent.$parent.getOrderSummary();
                        })
                        .catch((error) => {console.log(error);})
                },

                destroyCoupon() {
                    this.$axios.delete("{{ route('shop.checkout.cart.coupon.remove') }}", {
                            '_token': "{{ csrf_token() }}"
                        })
                        .then((response) => {

                            this.$emit('updateOrderSummary');

                            this.$parent.$parent.getOrderSummary();
                        })
                        .catch(error => console.log(error));
                },

                getAllCoupons() {
                    this.$axios.get("{{ route('shop.api.customers.cart_rules.index') }}")
                        .then((response) => {
                            console.log(response);
                            this.coupons = response.data.data;
                        })
                        .catch((error) => {
                            console.log(error);
                        })
                },

                assignCoupon(coupon) {
                    let isChecked = document.getElementById(coupon.code).checked;
                    
                    if (isChecked) {
                        this.code = coupon.code;
                    } else {
                        this.code = '';
                    }
                }
            }
        })

    </script>
@endPushOnce
