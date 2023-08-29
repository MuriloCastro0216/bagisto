<?php

namespace Webkul\Admin\Http\Controllers\Customer;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Core\Http\Requests\MassDestroyRequest;
use Webkul\Core\Http\Requests\MassUpdateRequest;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Admin\DataGrids\Customers\ReviewDataGrid;

class ReviewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductReviewRepository  $productReview
     * @return void
     */
    public function __construct(protected ProductReviewRepository $productReviewRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(ReviewDataGrid::class)->toJson();
        }

        return view('admin::customers.reviews.index');
    }

    /**
     * Review Details
     *
     * @param  int  $id
     * @return JsonResource
     */
    public function edit($id): JsonResource
    {
        $review = $this->productReviewRepository->with(['images', 'product'])->findOrFail($id);

        $review->date = $review->created_at->format('Y-m-d');

        return new JsonResource($review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        Event::dispatch('customer.review.update.before', $id);

        $review = $this->productReviewRepository->update(request()->only(['status']), $id);

        Event::dispatch('customer.review.update.after', $review);

        session()->flash('success', trans('admin::app.customers.reviews.update-success', ['name' => 'Review']));

        return redirect()->route('admin.customers.customers.review.index');
    }

    /**
     * Delete the review of the current product
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->productReviewRepository->findOrFail($id);

        try {
            Event::dispatch('customer.review.delete.before', $id);

            $this->productReviewRepository->delete($id);

            Event::dispatch('customer.review.delete.after', $id);

            return response()->json(['message' => trans('admin::app.customers.reviews.delete-success', ['name' => 'Review'])]);
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Review'])], 500);
    }

    /**
     * Mass delete the reviews on the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest)
    {
        $indices = $massDestroyRequest->input('indices');

        try {
            foreach ($indices as $index) {
                Event::dispatch('customer.review.delete.before', $index);

                $this->productReviewRepository->delete($index);

                Event::dispatch('customer.review.delete.after', $index);
            }

            return response()->json([
                'message' => trans('admin::app.customers.reviews.index.datagrid.mass-delete-success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Mass approve the reviews on the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest)
    {
        $indices = $massUpdateRequest->input('indices');
        $validStatuses = [0 => 'pending', 1 => 'approved', 2 => 'disapproved'];

        foreach ($indices as $value) {
            $review = $this->productReviewRepository->find($value);

            $newStatus = $validStatuses[$massUpdateRequest->input('value')] ?? null;

            if ($newStatus !== null) {
                if ($newStatus === 'approved') {
                    Event::dispatch('customer.review.update.before', $value);
                    Event::dispatch('customer.review.update.after', $review);
                }

                $review->update(['status' => $newStatus]);
            }
        }

        return response()->json([
            'message' => trans('admin::app.customers.reviews.index.datagrid.mass-update-success')
        ]);
    }
}
