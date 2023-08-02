<?php

namespace Webkul\Admin\Http\Controllers\Customer;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Admin\DataGrids\CustomerDataGrid;
use Webkul\Admin\DataGrids\CustomerOrderDataGrid;
use Webkul\Admin\DataGrids\CustomersInvoicesDataGrid;
use Webkul\Admin\Mail\NewCustomerNotification;
use Webkul\Admin\Mail\CustomerNoteNotification;
use Webkul\Customer\Repositories\CustomerNoteRepository;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected CustomerNoteRepository $customerNoteRepository
    ) 
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // if (request()->ajax()) {
        //     return app(CustomerDataGrid::class)->toJson();
        // }

        $groups = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        return view('admin::customers.index', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function store()
    {
        $this->validate(request(), [
            'first_name'    => 'string|required',
            'last_name'     => 'string|required',
            'gender'        => 'required',
            'email'         => 'required|unique:customers,email',
            'date_of_birth' => 'date|before:today',
            'phone'         => 'unique:customers,phone',
        ]);

        $password = rand(100000, 10000000);

        Event::dispatch('customer.registration.before');

        $data = array_merge(request()->only([
            'first_name',
            'last_name',
            'gender',
            'email',
            'date_of_birth',
            'phone',
            'customer_group_id',
        ]), [
            'password'    => bcrypt($password),
            'is_verified' => 1,
        ]);

        $customer = $this->customerRepository->create($data);

        Event::dispatch('customer.registration.after', $customer);

        if (core()->getConfigData('emails.general.notifications.emails.general.notifications.customer')) {
            try {
                Mail::queue(new NewCustomerNotification($customer, $password));
            } catch (\Exception $e) {
                report($e);
            }
        }

        return new JsonResource([
            'message' => trans('admin::app.customers.create-success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $customer = $this->customerRepository->findOrFail($id);

        $groups = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        return view('admin::customers.edit', compact('customer', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'first_name'    => 'string|required',
            'last_name'     => 'string|required',
            'gender'        => 'required',
            'email'         => 'required|unique:customers,email,' . $id,
            'date_of_birth' => 'date|before:today',
            'phone'         => 'unique:customers,phone,' . $id,
        ]);

        Event::dispatch('customer.update.before', $id);

        $data = array_merge(request()->only([
            'first_name',
            'last_name',
            'gender',
            'email',
            'date_of_birth',
            'phone',
            'customer_group_id',
        ]), [
            'status'       => request()->has('status'),
            'is_suspended' => request()->has('is_suspended'),
        ]);

        $customer = $this->customerRepository->update($data, $id);

        Event::dispatch('customer.update.after', $customer);

        session()->flash('success', trans('admin::app.customers.create-success'));

        return redirect()->route('admin.customer.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = $this->customerRepository->findorFail($id);

        try {
            if (! $this->customerRepository->checkIfCustomerHasOrderPendingOrProcessing($customer)) {
                $this->customerRepository->delete($id);

                return response()->json(['message' => trans('admin::app.customers.delete-success')]);
            }

            return response()->json(['message' => trans('admin::app.customers.order-pending')], 400);
        } catch (\Exception $e) {
        }

        return response()->json(['message' => trans('admin::app.customers.delete-failed')], 400);
    }

    /**
     * Login as customer
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAsCustomer($id)
    {
        $customer = $this->customerRepository->findOrFail($id);

        auth()->guard('customer')->login($customer);

        session()->flash('success', trans('admin::app.customers.loginascustomer.login-message', ['customer_name' => $customer->name]));

        return redirect(route('shop.customers.account.profile.index'));
    }

      /**
     * To store the response of the note.
     *
     * @param  int  $id
     * @return \Illuminate\Http\View
     */
    public function storeNotes($id)
    {
        $this->validate(request(), [
            'note' => 'string|required',
        ]);

        $this->customerNoteRepository->create([
            'customer_id'       => $id,
            'note'              => request()->input('note'),
            'customer_notified' => request()->input('customer_notified', 0),
        ]);

        $customer = $this->customerRepository->find($id);

        if (request()->has('customer_notified')) {
            try {
                Mail::queue(new CustomerNoteNotification($customer, request()->input('note', 'email')));
            } catch(\Exception$e) {
                session()->flash('warning', $e->getMessage());
            }
        }

        session()->flash('success', trans('admin::app.customers.view.note-created-success'));

        return redirect()->back();
    }

    /**
     * To mass update the customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate()
    {
        $customerIds = explode(',', request()->input('indexes'));

        $updateOption = request()->input('update-options');

        foreach ($customerIds as $customerId) {
            Event::dispatch('customer.update.before', $customerId);

            $customer = $this->customerRepository->update([
                'status' => $updateOption,
            ], $customerId);

            Event::dispatch('customer.update.after', $customer);
        }

        session()->flash('success', trans('admin::app.customers.customers.mass-update-success'));

        return redirect()->back();
    }

    /**
     * To mass delete the customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $customerIds = explode(',', request()->input('indexes'));

        if (! $this->customerRepository->checkBulkCustomerIfTheyHaveOrderPendingOrProcessing($customerIds)) {
            foreach ($customerIds as $customerId) {
                Event::dispatch('customer.delete.before', $customerId);

                $this->customerRepository->delete($customerId);

                Event::dispatch('customer.delete.after', $customerId);
            }

            session()->flash('success', trans('admin::app.customers.customers.mass-destroy-success'));

            return redirect()->back();
        }

        session()->flash('error', trans('admin::app.customers.order-pending'));

        return redirect()->back();
    }

    /**
     * Retrieve all invoices from customer.
     *
     * @param  int  $id
     * @return \Webkul\Admin\DataGrids\CustomersInvoicesDataGrid
     */
    public function invoices($id)
    {
        if (request()->ajax()) {
            return app(CustomersInvoicesDataGrid::class)->toJson();
        }
    }

    /**
     * Retrieve all orders from customer.
     *
     * @param  int  $id
     * @return \Webkul\Admin\DataGrids\CustomerOrderDataGrid
     */
    public function orders($id)
    {
        if (request()->ajax()) {
            return app(CustomerOrderDataGrid::class)->toJson();
        }

        $customer = $this->customerRepository->find(request('id'));

        return view('admin::customers.orders.index', compact('customer'));
    }

    /**
     * View all details of customer.
     *
     * @param  int  $id
     * @return
     */
    public function show($id)
    {
        $customer = $this->customerRepository->with(['orders', 'invoices', 'reviews', 'notes', 'addresses'])->findOrFail($id);

        $groups = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        return view('admin::customers.view', compact('customer', 'groups'));
    }
}
