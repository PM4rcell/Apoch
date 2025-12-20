<?php

namespace App\Http\Controllers;

use App\Models\BookingProduct;
use App\Http\Requests\StoreBooking_productRequest;
use App\Http\Requests\UpdateBooking_productRequest;
use App\Http\Resources\BookingProductResource;

class BookingProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = BookingProduct::query()->with(['booking', 'productType'])->paginate(15);
        return BookingProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBooking_productRequest $request)
    {
        $data = $request->validated();
        $product = BookingProduct::create($data);

        $product->load('booking', 'productType');
        return new BookingProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(BookingProduct $bookingProduct)
    {
        $bookingProduct->load('booking', 'productType');
        return new BookingProductResource($bookingProduct);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBooking_productRequest $request, BookingProduct $bookingProduct)
    {
        $data = $request->validated();
        $bookingProduct->update($data);

        $bookingProduct->load('booking', 'productType');
        return new BookingProductResource($bookingProduct);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BookingProduct $bookingProduct)
    {
        $bookingProduct->delete();
        return response()->noContent();
    }
}
