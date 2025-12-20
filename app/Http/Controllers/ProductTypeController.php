<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Http\Requests\StoreProduct_typeRequest;
use App\Http\Requests\UpdateProduct_typeRequest;
use App\Http\Resources\ProductTypeResource;
use App\Services\MediaService;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productTypes = ProductType::query()->with('poster')->paginate(15);
        return ProductTypeResource::collection($productTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProduct_typeRequest $request, MediaService $mediaService)
    {
        $data = $request->validated();
        $productType = ProductType::create($data);

        if(!empty($data['external_url'])){
            $mediaService->storeExternalPoster($productType, $data['external_url']);
        }
        elseif($request->hasFile('poster')){
            $mediaService->storeUploadedPoster($productType, $request->file('poster'));
        }

        $productType->load('poster');
        return new ProductTypeResource($productType->fresh());
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductType $productType)
    {
        $productType->load('poster');
        return new ProductTypeResource($productType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProduct_typeRequest $request, ProductType $productType, MediaService $mediaService)
    {
        $data = $request->validated();
        $productType->update($data);

        if(!empty($data['external_url'])){
            $mediaService->storeExternalPoster($productType, $data['external_url']);
        }
        elseif($request->hasFile('poster')){
            $mediaService->storeUploadedPoster($productType, $request->file('poster'));
        }

        $productType->load('poster');
        return new ProductTypeResource($productType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductType $productType)
    {
        $productType->delete();
        return response()->noContent();
    }
}
