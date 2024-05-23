<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\ProductStoreRequest;
use App\Models\Image;
use App\Models\Product;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use GeneralTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $timestamp = Carbon::now();
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'description' => $request->description
        ]);

        $this->storeImage($request, $product, $timestamp);

        return $this->returnSuccessMessage('Product added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    public function storeImage($request, $product, $timestamp)
    {
        $imageData = [];
        $images = $request->file('image_url');

        foreach ($images as $image) {
            $imageName = $this->uploadImage($image, "Products\\");
            $imageData[] = [
                'image_url' => $imageName,
                'product_id' => $product->id,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        Image::insert($imageData);
    }
}
